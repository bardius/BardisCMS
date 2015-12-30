<?php

/*
 * User Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\XMLFileLoader;
use Symfony\Component\Config\FileLocator;

// The service for the custon registration form type should load in the services.xml of the bundle
// but this extension is totally ignored so the service is declared in the app/config/services.xml
// this must be fixed so bundle and app are decoupled properly
class ApplicationSonataUserExtension extends Extension {

    public function load(array $configs, ContainerBuilder $container) {
        $loader = new XMLFileLoader(
                $container, new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.xml');
    }

}
