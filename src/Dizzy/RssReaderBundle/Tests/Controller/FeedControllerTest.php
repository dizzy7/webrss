<?php

namespace Dizzy\RssReaderBundle\Tests\Controller;

use Dizzy\RssReaderBundle\Tests\RssWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class FeedControllerTest extends RssWebTestCase
{
    /** @var Client */
    private $client;

    public function testAddFeed()
    {
        $crawler = $this->client->request('GET', '/feed/new/');
        $this->assertTrue($crawler->filter('html:contains("Добавить")')->count() > 0);
        $form    = $crawler->filter('#dizzy_rssreaderbundle_feed_submit')->form();
        $form['dizzy_rssreaderbundle_feed[url]']='http://local';
        $this->client->submit($form);

        $crawler = $this->client->getCrawler();
        $this->assertTrue($crawler->filter('html:contains("Не удалось получить данные, проверьте ссылку")')->count()>0);
    }


    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->login($this->client);
    }
}
