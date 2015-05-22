<?php

namespace Dizzy\RssReaderBundle\Services;

use Dizzy\RssReaderBundle\Document\Feed;
use Dizzy\RssReaderBundle\Document\User;
use Dizzy\RssReaderBundle\Interfaces\ImportInterface;
use Dizzy\RssReaderBundle\Interfaces\RssFetcherInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportOPML implements ImportInterface
{
    /** @var DocumentManager */
    private $documentManager;
    /** @var RssFetcherInterface */
    private $fetcher;

    public function __construct(DocumentManager $documentManager, RssFetcherInterface $fetcher)
    {
        $this->documentManager = $documentManager;
        $this->fetcher = $fetcher;
    }

    public function importFile(User $user, UploadedFile $file)
    {
        $xml        = simplexml_load_file($file->getPathname());
        $count      = 0;
        $userFeeds  = $user->getFeeds();

        foreach ($xml->body->outline as $item) {
            $feed = $this->documentManager->getRepository('DizzyRssReaderBundle:Feed')->findOneBy(
                ['url' => (string)$item['xmlUrl']]
            );
            if ($feed === null) {
                $feed = new Feed();
                $feed->setUrl((string)$item['xmlUrl']);
                $feed->setTitle((string)$item['text']);
                $this->documentManager->persist($feed);
                $this->documentManager->flush();

                $user->addFeed($feed);
                $feed->addUser($user);
                $this->documentManager->flush();

                $this->fetcher->fetchFeed($feed);
            } else {
                if (!$userFeeds->contains($feed)) {
                    $user->addFeed($feed);
                    $this->documentManager->refresh($feed);
                }
            }

            $count++;
        }

        $this->documentManager->flush();

        return $count;
    }
}
