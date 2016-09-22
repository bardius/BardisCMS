<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Sonata\UserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XMLFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ApplicationSonataUserExtension extends Extension
{
    /*
     * The services for the Sonata User bundle overrides
     *
     * @param array $configs
     * @param ContainerBuilder $container
     *
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XMLFileLoader(
                $container, new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');
    }
}
