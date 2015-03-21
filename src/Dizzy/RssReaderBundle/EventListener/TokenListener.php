<?php


namespace Dizzy\RssReaderBundle\EventListener;

use Dizzy\RssReaderBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class TokenListener
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function onKernelController(FilterControllerEvent $event)
    {
        if ($this->container->get('security.context')->isGranted('ROLE_USER')) {
            return;
        }

        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedController) {
            if($event->getRequest()->attributes->get('_route') == 'api_get_token'){
                return true;
            }

            $token = $event->getRequest()->request->get('token');
            if ($token === null) {
                throw new AccessDeniedHttpException('Token or login needed');
            }
        }
    }


} 