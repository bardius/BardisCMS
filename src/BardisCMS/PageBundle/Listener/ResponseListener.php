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

class ResponseListener {

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->set('X-UA-Compatible', 'IE=Edge,chrome=1');
        $event->getResponse()->headers->set('P3P', 'cp=BardisCMS');
        $event->getResponse()->headers->set('X-XSS-Protection', '1; mode=block');
        $event->getResponse()->headers->set('X-Content-Type-Options', 'nosniff');
        $event->getResponse()->headers->set('x-frame-options', 'deny');
        $event->getResponse()->headers->set('ServerSignature', 'Off');
        $event->getResponse()->headers->set('ServerTokens', 'Prod');
        $event->getResponse()->headers->set('Content-Language', 'en');
        $event->getResponse()->headers->set('Created-By', 'George Bardis - george@bardis.info');

        $event->getResponse()->headers->unset('link');
        $event->getResponse()->headers->unset('Server');
        $event->getResponse()->headers->unset('X-Pingback');
    }
}
