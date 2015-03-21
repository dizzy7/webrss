<?php

namespace Dizzy\RssReaderBundle\Command;

use Dizzy\RssReaderBundle\Entity\Feed;
use Dizzy\RssReaderBundle\Interfaces\RssFetcherInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FetchFeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('rss:fetch')
            ->setDescription('Fetch all feeds');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        $feeds = $entityManager->getRepository('DizzyRssReaderBundle:Feed')->findAll();

        /** @var RssFetcherInterface $fetcher */
        $fetcher = $this->getContainer()->get('rss.fetch');

        foreach ($feeds as $feed) {
            $fetcher->fetchFeed($feed);
        }
    }
}
