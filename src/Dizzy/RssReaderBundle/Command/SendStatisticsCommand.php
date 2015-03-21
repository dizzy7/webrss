<?php

namespace Dizzy\RssReaderBundle\Command;

use DateInterval;
use DateTime;
use Dizzy\RssReaderBundle\Entity\Post;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendStatisticsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('rss:statistic:send')
            ->setDescription('Отправка статистики за последние сутки на почту');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        $date = new DateTime();
        $date->sub(new DateInterval('P1D'));

        $newPosts = $entityManager->getRepository('DizzyRssReaderBundle:Post')->getPostsCountForDay($date);
        $newUsers = $entityManager->getRepository('DizzyRssReaderBundle:User')->getNewUsersCountForDay($date);

        $message = \Swift_Message::newInstance()
            ->setSubject('Статистика сервиса rss.dizzy.name за ' . $date->format('d.m.Y'))
            ->setFrom('noreply@rss.dizzy.name')
            ->setTo('7dizzy7@gmail.com')
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    '@DizzyRssReader/_mail/statistics.txt.twig',
                    [
                        'postsCount' => $newPosts,
                        'usersCount' => $newUsers
                    ]
                )
            );

        $this->sendEmail($message);

        return 0;
    }

    private function sendEmail($message)
    {
        $mailer = $this->getContainer()->get('mailer');
        $mailer->send($message);
    }
}
