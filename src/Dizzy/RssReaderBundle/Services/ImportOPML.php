<?php

namespace Dizzy\RssReaderBundle\Services;

use Dizzy\RssReaderBundle\Entity\Feed;
use Dizzy\RssReaderBundle\Entity\User;
use Dizzy\RssReaderBundle\Interfaces\ImportInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImportOPML implements ImportInterface
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

    public function importFile(User $user, UploadedFile $file)
    {
        $xml        = simplexml_load_file($file->getPathname());
        $count      = 0;
        $rssfetcher = $this->container->get('rss.fetch');
        $userFeeds  = $user->getFeeds();

        foreach ($xml->body->outline as $item) {
            $feed = $this->entityManager->getRepository('DizzyRssReaderBundle:Feed')->findOneBy(
                ['url' => (string)$item['xmlUrl']]
            );
            if ($feed === null) {
                $feed = new Feed();
                $feed->setUrl((string)$item['xmlUrl']);
                $feed->setTitle((string)$item['text']);
                $this->entityManager->persist($feed);
                $this->entityManager->flush();

                $user->addFeed($feed);
                $feed->addUser($user);
                $this->entityManager->flush();

                $rssfetcher->fetchFeed($feed);
            } else {
                if (!$userFeeds->contains($feed)) {
                    $user->addFeed($feed);
                    $this->entityManager->refresh($feed);
                }
            }

            $count++;
        }

        $this->entityManager->flush();

        return $count;
    }
}
