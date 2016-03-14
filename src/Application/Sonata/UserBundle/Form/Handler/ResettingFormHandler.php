<?php

/*
 * Sonata User Bundle Overrides
 * This file is part of the BardisCMS.
 * Manage the extended Sonata User entity with extra information for the users
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Form\Handler;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Form\Handler\ResettingFormHandler as BaseHandler;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ResettingFormHandler extends BaseHandler
{
    private $container;

    /**
     * Construct handler for ResettingFormHandler
     *
     * @param FormInterface $form
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @param Container $container
     *
     */
    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, Container $container)
    {
        parent::__construct($form, $request, $userManager);
        $this->container = $container;
    }

    /**
     * Extend with a method that returns the errors of the process
     *
     * @return Array
     */
    public function getErrors()
    {
        return $this->container->get('bardiscms_page.services.helpers')->getFormErrorMessages($this->form);
    }
}
