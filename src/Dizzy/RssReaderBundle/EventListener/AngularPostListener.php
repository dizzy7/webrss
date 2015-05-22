<?php


namespace Dizzy\RssReaderBundle\EventListener;

use Dizzy\RssReaderBundle\Interfaces\TokenAuthenticatedController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class AngularPostListener
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }

    }

} 