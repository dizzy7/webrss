<?php

namespace Dizzy\RssReaderBundle\DataFixtures\ORM;

use DateTime;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Dizzy\RssReaderBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{


    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin')->setEmail('admin@rss.dizzy.name')->setPlainPassword('adminpass');
        $userAdmin->setEnabled(true);
        $userAdmin->addRole("ROLE_ADMIN");

        $user = new User;
        $user->setUsername('user')->setEmail('user@rss.dizzy.name')->setPlainPassword('userpass');
        $expire = new DateTime();
        $expire->add(new \DateInterval('P1D'));
        $user->setMobileToken('11111')->setMobileTokenExpire($expire);
        $user->setEnabled(true);

        $manager->persist($userAdmin);
        $manager->persist($user);
        $manager->flush();

        $this->addReference('user_admin', $userAdmin);
        $this->addReference('user_user', $user);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}
