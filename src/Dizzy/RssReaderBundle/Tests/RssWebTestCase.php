<?php


namespace Dizzy\RssReaderBundle\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

class RssWebTestCase extends WebTestCase
{

    private $defaultUser = 'user';
    private $defaultUserPassword = 'userpass';

    /** @var EntityManager */
    protected $entityManager;

    public function login(Client $client, $username = null, $password = null)
    {
        $crawler = $client->request('GET', '/login');
        $form    = $crawler->filter('#_submit')->form(
            [
                '_username' => $username ?: $this->defaultUser,
                '_password' => $password ?: $this->defaultUserPassword
            ]
        );
        $client->submit($form);
        $client->followRedirect();

//        $crawler = $client->request('GET','/');
//
//        $this->assertTrue($crawler->filter('html:contains("user")')->count() > 0);
//        $this->assertTrue($crawler->filter('html:contains("Выйти")')->count() > 0);
    }

    public function setUp()
    {
        $entityManager = static::createClient()->getContainer()->get('doctrine')->getManager();

        $loader = new Loader();
        $loader->loadFromDirectory('src/Dizzy/RssReaderBundle/DataFixtures/ORM');
        $purger   = new ORMPurger($entityManager);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());

        $client              = static::createClient();
        $this->entityManager = $client->getContainer()->get('doctrine')->getManager();
    }
}
