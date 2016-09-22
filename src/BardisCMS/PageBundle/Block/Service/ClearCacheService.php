<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\PageBundle\Block\Service;

use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClearCacheService extends BaseBlockService
{
    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderPrivateResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Clear Cache';
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'icon' => 'fa-line-chart',
            'title' => 'Clear Cache',
            'text' => 'Clear/purge the Symfony2 HTTP cache.',
            'color' => 'bg-aqua',
            'code' => false,
            'filters' => array(),
            'template' => 'PageBundle:Block:block_clear_cache.html.twig',
        ));
    }
}
