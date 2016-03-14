<?php

/*
 * Sonata User Bundle Overrides
 * This file is part of the BardisCMS.
 * Manage the extended Sonata User entity with extra information for the users
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

class UserExtension extends Extension {

    /*
     * The services for the Sonata User bundle
     *
     * @param array $configs
     * @param ContainerBuilder $container
     *
     */
    public function load(array $configs, ContainerBuilder $container) {
        $loader = new XMLFileLoader(
                $container, new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.xml');
    }

}
