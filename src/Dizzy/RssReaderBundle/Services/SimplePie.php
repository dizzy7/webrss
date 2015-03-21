<?php


namespace Dizzy\RssReaderBundle\Services;


use DateTime;
use Dizzy\RssReaderBundle\Entity\Feed;
use Dizzy\RssReaderBundle\Entity\Post;
use Dizzy\RssReaderBundle\Interfaces\RssFetcherInterface;
use Doctrine\ORM\EntityManager;
use SimplePie_Item;
use Symfony\Component\DependencyInjection\ContainerInterface;


class SimplePie implements RssFetcherInterface
{

    /** @var ContainerInterface */
    private $container;
    /** @var EntityManager */
    private $entityManager;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container     = $container;
        $this->entityManager = $container->get('doctrine')->getManager();
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
                $post->setFeed($feed);

                foreach ($users as $user) {
                    $post->addUnreadByUser($user);
                }

                $this->entityManager->persist($post);
                $this->entityManager->flush();
                $this->entityManager->flush();
            }
        }

        $feed->setLastcheck(new DateTime());
        $this->entityManager->flush();
    }
}
