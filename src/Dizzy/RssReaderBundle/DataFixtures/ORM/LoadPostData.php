<?php

namespace Dizzy\RssReaderBundle\DataFixtures\ORM;

use DateTime;
use Dizzy\RssReaderBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{


    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $post1 = new Post();
        $post1->setTitle('First post')->setUrl('http://post1')->setBody('first post text')->setCreated(
            new DateTime('2014-01-01 01:00:00')
        );
        $post1->setFeed($this->getReference('feed_1'));
        $post1->addUnreadByUser($this->getReference('user_user'));
        $manager->persist($post1);

        $manager->flush();

        $post2 = new Post();
        $post2->setTitle('Second post')->setUrl('http://post2')->setBody('second post text')->setCreated(
            new DateTime('2014-01-02 01:00:00')
        );
        $post2->setFeed($this->getReference('feed_1'));
        $post2->addUnreadByUser($this->getReference('user_user'));
        $manager->persist($post2);

        $post3 = new Post();
        $post3->setTitle('Old post')->setUrl('http://post3')->setBody('old post text')->setCreated(
            new DateTime('2013-01-02 00:00:00')
        );
        $post3->setFeed($this->getReference('feed_1'));
        $manager->persist($post3);

        $manager->flush();

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 30;
    }
}
