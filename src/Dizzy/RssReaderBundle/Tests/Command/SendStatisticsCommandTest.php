<?php


namespace Dizzy\RssReaderBundle\Tests\Command;

use Dizzy\RssReaderBundle\Command\SendStatisticsCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SendStatisticsCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);

        $mailer = $this->getMock('Swift_Mailer',['send'],[],'',false);
        $mailer->expects($this->once())->method('send');
        $kernel->getContainer()->set('swiftmailer.mailer.default', $mailer);

        $application->add(new SendStatisticsCommand());
        $command = $application->find('rss:statistic:send');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertEquals($commandTester->getStatusCode(), 0);
    }
}
