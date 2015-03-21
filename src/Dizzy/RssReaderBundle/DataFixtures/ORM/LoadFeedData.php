<?php

namespace Dizzy\RssReaderBundle\DataFixtures\ORM;

use Dizzy\RssReaderBundle\Entity\Feed;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFeedData extends AbstractFixture implements OrderedFixtureInterface
{


    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $feed1 = new Feed();
        $feed1->setTitle('First feed')->setUrl('http://feed1');
        $this->getReference('user_user')->addFeed($feed1);

        $feed2 = new Feed();
        $feed2->setTitle('Second feed')->setUrl('http://feed2');
        $feed2->addUser($this->getReference('user_user'));
        $this->getReference('user_user')->addFeed($feed2);

        $manager->persist($feed1);
        $manager->persist($feed2);
        $manager->flush();

        $this->addReference('feed_1', $feed1);
        $this->addReference('feed_2', $feed1);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 20;
    }
}
