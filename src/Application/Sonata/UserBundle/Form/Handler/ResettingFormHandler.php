<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Sonata\UserBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\ResettingFormHandler as BaseHandler;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ResettingFormHandler extends BaseHandler
{
    private $container;

    /**
     * Construct handler for ResettingFormHandler.
     *
     * @param FormInterface        $form
     * @param Request              $request
     * @param UserManagerInterface $userManager
     * @param Container            $container
     */
    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, Container $container)
    {
        parent::__construct($form, $request, $userManager);
        $this->container = $container;
    }

    /**
     * Extend with a method that returns the errors of the process.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->container->get('bardiscms_page.services.helpers')->getFormErrorMessages($this->form);
    }
}
