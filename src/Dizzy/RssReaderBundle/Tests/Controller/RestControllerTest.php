<?php

namespace Dizzy\RssReaderBundle\Tests\Controller;

use Dizzy\RssReaderBundle\Entity\Post;
use Dizzy\RssReaderBundle\Tests\RssWebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class RestControllerTest extends RssWebTestCase
{
    /** @var Client */
    private $client;

    public function testAccess()
    {
        $this->client->request(
            'POST',
            '/api/getFeeds/',
            [
                'token' => '11111'
            ]
        );

        $content = $this->client->getResponse()->getContent();
        $json    = json_decode($content, true);
        $this->assertTrue(is_array($json));
        $this->assertTrue(count($json) == 1);
    }

    public function testFeeds()
    {
        $this->client->request(
            'POST',
            '/api/getFeeds/',
            [
                'token' => '11111'
            ]
        );

        $content = $this->client->getResponse()->getContent();
        $json    = json_decode($content, true);
        $this->assertTrue(is_array($json));
        $this->assertTrue(count($json) == 1);
        $feed = $json[0];
        $this->assertArrayHasKey('title', $feed);
        $this->assertTrue($feed['title'] == 'First feed');
        $this->assertTrue($feed['url'] == 'http://feed1');
        $this->assertTrue($feed['unreadCount'] == 2);
    }

    public function testAllPosts()
    {
        $this->client->request(
            'POST',
            '/api/getPosts/all/2000-01-01/',
            [
                'token' => '11111'
            ]
        );
        $content = $this->client->getResponse()->getContent();
        $json    = json_decode($content, true);
        $this->assertTrue(is_array($json));
        $this->assertTrue(count($json) == 2);
    }

    public function testPostsFilterByFeed()
    {
        $feed1 = $this->entityManager->getRepository('DizzyRssReaderBundle:Feed')->findOneBy(['title' => 'First feed']);
        $feed2 = $this->entityManager->getRepository('DizzyRssReaderBundle:Feed')->findOneBy(
            ['title' => 'Second feed']
        );

        $this->client->request(
            'POST',
            '/api/getPosts/' . $feed1->getId() . '/2000-01-01/',
            [
                'token' => '11111'
            ]
        );
        $content = $this->client->getResponse()->getContent();
        $json    = json_decode($content, true);
        $this->assertTrue(is_array($json));
        $this->assertTrue(count($json) == 2);

        $this->client->request(
            'POST',
            '/api/getPosts/' . $feed2->getId() . '/2000-01-01/',
            [
                'token' => '11111'
            ]
        );
        $content = $this->client->getResponse()->getContent();
        $json    = json_decode($content, true);
        $this->assertTrue(is_array($json));
        $this->assertTrue(count($json) == 0);
    }

    public function testPostsFilterByDate()
    {
        $feed1 = $this->entityManager->getRepository('DizzyRssReaderBundle:Feed')->findOneBy(['title' => 'First feed']);

        $this->client->request(
            'POST',
            '/api/getPosts/' . $feed1->getId() . '/2014-01-02/',
            [
                'token' => '11111'
            ]
        );
        $content = $this->client->getResponse()->getContent();
        $json    = json_decode($content, true);
        $this->assertTrue(is_array($json));
        $this->assertTrue(count($json) == 1);

        $this->client->request(
            'POST',
            '/api/getPosts/' . $feed1->getId() . '/2014-01-03/',
            [
                'token' => '11111'
            ]
        );
        $content = $this->client->getResponse()->getContent();
        $json    = json_decode($content, true);
        $this->assertTrue(is_array($json));
        $this->assertTrue(count($json) == 0);
    }

    public function testSetPostRead()
    {
        $user = $this->entityManager->getRepository('DizzyRssReaderBundle:User')->findOneBy(['username' => 'user']);
        /** @var Post[] $posts */
        $posts = $user->getUnreadPosts();
        $this->assertTrue(count($posts) == 2);
        foreach ($posts as $post) {
            $this->client->request(
                'POST',
                '/api/setPostRead/' . $post->getId() . '/',
                [
                    'token' => '11111'
                ]
            );
            break;
        }

        $this->client->request(
            'POST',
            '/api/getPosts/all/2000-01-01/',
            [
                'token' => '11111'
            ]
        );
        $content = $this->client->getResponse()->getContent();
        $json    = json_decode($content, true);
        $this->assertTrue(count($json) == 1);
    }

    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->login($this->client);
    }
}
