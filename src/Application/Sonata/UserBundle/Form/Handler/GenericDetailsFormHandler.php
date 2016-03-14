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

use Application\Sonata\UserBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class GenericDetailsFormHandler
{
    protected $request;
    protected $userManager;
    protected $form;
    private $container;

    /**
     * Construct handler for GenericDetailsFormHandler
     *
     * @param FormInterface $form
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @param Container $container
     *
     */
    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, Container $container)
    {
        $this->form = $form;
        $this->request = $request;
        $this->userManager = $userManager;
        $this->container = $container;
    }

    /**
     * Process the form for GenericDetailsForm
     *
     * @param UserInterface $user
     *
     * @return boolean
     */
    public function process(UserInterface $user)
    {
        $this->form->setData($user);

        if ('POST' === $this->request->getMethod() && $this->request->request->has('sonata_user_generic_details_form')) {

            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $newUserData = $this->form->getData();

                $this->onSuccess($newUserData);

                return true;
            }
        }

        return false;
    }

    /**
     * onSuccess after processing the form for GenericDetailsForm
     *
     * @param UserInterface $user
     *
     */
    protected function onSuccess(UserInterface $user)
    {
        $this->userManager->updateUser($user);
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
