<?php


namespace Dizzy\RssReaderBundle\Services;


use DateTime;
use Dizzy\RssReaderBundle\Document\Feed;
use Dizzy\RssReaderBundle\Document\Post;
use Dizzy\RssReaderBundle\Document\UnreadReference;
use Dizzy\RssReaderBundle\Interfaces\RssFetcherInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Dizzy\RssReaderBundle\Document\User;
use SimplePie_Item;
use Symfony\Component\DependencyInjection\ContainerInterface;


class SimplePie implements RssFetcherInterface
{
    /** @var DocumentManager */
    private $documentManager;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface $container
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function fetchFeed(Feed $feed)
    {
        $users = $feed->getUsers();

        $sp = new \SimplePie();
        $sp->enable_cache(false);
        $sp->set_feed_url($feed->getUrl());
        @$sp->init();
        /** @var SimplePie_item $item */
        foreach ($sp->get_items() as $item) {
            $itemDate = DateTime::createFromFormat('Y-m-d H:i:s', $item->get_date('Y-m-d H:i:s'));
            if ($itemDate >= $feed->getLastcheck()) {
                $post = new Post();
                $post->setTitle($item->get_title());
                $post->setBody($item->get_content());
                $post->setUrl($item->get_link());
                $post->setCreated($itemDate);

                $feed->addPost($post);

                /** @var User $user */
                foreach ($users as $user) {
                    //TODO ugly
                    /** @var UnreadReference $unreadFeed */
                    if ($user->getUnreadFeeds()) {
                        $unreadFeed = $user->getUnreadFeeds()->filter(
                            function(UnreadReference $unreadReference) use ($feed) {
                                return $unreadReference->getFeed()->getId() === $feed->getId();
                            }
                        );
                    }

                    if (!isset($unreadFeed) || !$unreadFeed) {
                        $unread = new UnreadReference();
                        $unread->setFeed($feed);
                        $unread->setUnreadCount(1);
                        $user->addUnreadFeed($unread);
                    } else {
                        $unreadFeed->setUnreadCount($unreadFeed->getUnreadCount()+1);
                    }
                }

                $this->documentManager->persist($post);
                $this->documentManager->flush();
            }
        }

        $feed->setLastcheck(new DateTime());
        $this->documentManager->flush();
    }
}
