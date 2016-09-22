<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\SettingsBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XMLFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SettingsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XMLFileLoader(
                $container, new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');
    }

    public function getAlias()
    {
        return 'settings';
    }
}
