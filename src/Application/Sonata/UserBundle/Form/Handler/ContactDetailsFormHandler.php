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

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ContactDetailsFormHandler
{
    protected $request;
    protected $userManager;
    protected $form;
    private $container;

    /**
     * Construct handler for ContactDetailsFormHandler.
     *
     * @param FormInterface        $form
     * @param Request              $request
     * @param UserManagerInterface $userManager
     * @param Container            $container
     */
    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, Container $container)
    {
        $this->form = $form;
        $this->request = $request;
        $this->userManager = $userManager;
        $this->container = $container;
    }

    /**
     * Process the form for ContactDetailsForm.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function process(UserInterface $user)
    {
        $this->form->setData($user);

        if ('POST' === $this->request->getMethod() && $this->request->request->has('sonata_user_contact_details_form')) {
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
     * onSuccess after processing the form for ContactDetailsForm.
     *
     * @param UserInterface $user
     */
    protected function onSuccess(UserInterface $user)
    {
        $this->userManager->updateUser($user);
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
