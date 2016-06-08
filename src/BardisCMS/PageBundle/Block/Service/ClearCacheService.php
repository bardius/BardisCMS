<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Block\Service;

use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClearCacheService extends BaseBlockService {

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null) {

        return $this->renderPrivateResponse($blockContext->getTemplate(), array(
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings()
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'Clear Cache';
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver) {
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
