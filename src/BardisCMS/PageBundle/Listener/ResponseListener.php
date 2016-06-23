<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Cookie;

class ResponseListener {

    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event) {
        $kernel    = $event->getKernel();
        $request   = $event->getRequest();
    }

    public function onKernelResponse(FilterResponseEvent $event) {
        $headers    = $event->getResponse()->headers;
        $request    = $event->getRequest();
        $kernel     = $event->getKernel();

        // Sample on how to add a cookie in all the responses
        /*
        $userStatusCookieValue = $request->cookies->get('bardiscms_user_status');

        if ($userStatusCookieValue != $this->userRole) {
            $userStatusCookie = new Cookie('bardiscms.user.status', '', time() + 3600 * 24 * 1, '/', null, false, true);
            //$headers->setCookie($userStatusCookie);
        }
        */

        // TODO: Add these into the htaccess instead
        $headers->set('X-UA-Compatible', 'IE=Edge,chrome=1');
        $headers->set('P3P', 'cp=BardisCMS');
        $headers->set('X-Frame-Options', 'deny');
        $headers->set('X-XSS-Protection', '1; mode=block');
        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('ServerSignature', 'Off');
        $headers->set('ServerTokens', 'Prod');
        $headers->set('Content-Language', 'en');
        $headers->set('Created-By', 'George Bardis - george@bardis.info');
        $headers->set('Version', '1.0.0');
    }
}
