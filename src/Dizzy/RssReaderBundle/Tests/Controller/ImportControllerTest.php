<?php

namespace Dizzy\RssReaderBundle\Tests\Controller;

use Dizzy\RssReaderBundle\Tests\RssWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class ImportControllerTest extends RssWebTestCase
{
    /** @var Client */
    private $client;

    public function testOpmlIndex()
    {
        $crawler = $this->client->request('GET', '/import/opml/');

        $this->assertTrue($crawler->filter('html:contains("Импорт OPML")')->count() > 0);
    }

    public function testOpmlImport()
    {
        $crawler = $this->client->request('GET', '/import/opml/');
        $form    = $crawler->filter('#dizzy_rssreaderbundle_import_submit')->form();
        $form['dizzy_rssreaderbundle_import[file]']->upload(__DIR__ . '/test.opml');

        $this->client->submit($form);

        $crawler = $this->client->getCrawler();
        $this->assertTrue($crawler->filter('html:contains("Импортировано 1 лент")')->count() > 0);

        $feed = $this->entityManager
            ->getRepository('DizzyRssReaderBundle:Feed')
            ->findOneBy(['title' => 'Хабрахабр']);
        $this->assertNotNull($feed);
        $this->assertEquals($feed->getUrl(), 'http://habrahabr.ru/rss/');
        $this->assertEquals(count($feed->getUsers()), 1);
    }


    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->login($this->client);
    }
}
