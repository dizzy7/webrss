<?php


namespace Dizzy\RssReaderBundle\Tests\Command;

use Dizzy\RssReaderBundle\Command\FetchFeedCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class FetchFeedCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);

        $mailer = $this->getMock('Dizzy\RssReaderBundle\Services\SimplePie',['fetchFeed'],[],'',false);
        $mailer->expects($this->atLeastOnce())->method('fetchFeed');
        $kernel->getContainer()->set('rss.fetch', $mailer);

        $application->add(new FetchFeedCommand());
        $command = $application->find('rss:fetch');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertEquals($commandTester->getStatusCode(), 0);
    }
}
