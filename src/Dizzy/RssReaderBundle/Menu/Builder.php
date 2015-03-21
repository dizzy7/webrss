<?php

namespace Dizzy\RssReaderBundle\Menu;

use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');

        $securityContext = $this->container->get('security.context');

        $menu->addChild('На главную', ['route' => 'index']);
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $rss = $menu->addChild('RSS');
            $rss->addChild('Добавить RSS', ['route' => 'feed_new']);
            $rss->addChild('Упраление RSS', ['route' => 'feed_edit']);
            $rss->addChild('Импорт OPML', ['route' => 'import_opml']);
        }
        if ($securityContext->isGranted('ROLE_ADMIN')) {
            $admin = $menu->addChild('Администрирование');
            $admin->addChild('Статистика', ['route' => 'admin_stat']);
        }

        return $menu;
    }

    public function feedMenu(FactoryInterface $factory)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user        = $userManager->findUserByUsername(
            $this->container->get('security.context')
                ->getToken()
                ->getUser()
        );

        $menu = $factory->createItem('root');
        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine')->getManager();
        $result        = $entityManager->getRepository('DizzyRssReaderBundle:Feed')->getUserFeed($user);
        foreach ($result as $item) {
            list($feed, $count) = $item;

            $menu->addChild(
                $feed->getTitle() . ' (' . $count . ')',
                ['route' => 'readFeed', 'routeParameters' => ['feed' => $feed->getId()]]
            );
        }

        return $menu;
    }
}
