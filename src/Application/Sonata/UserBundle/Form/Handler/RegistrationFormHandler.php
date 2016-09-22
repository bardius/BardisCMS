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

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RegistrationFormHandler extends BaseHandler
{
    private $container;

    /**
     * Construct handler for RegistrationFormHandler.
     *
     * @param FormInterface           $form
     * @param Request                 $request
     * @param UserManagerInterface    $userManager
     * @param MailerInterface         $mailer
     * @param TokenGeneratorInterface $tokenGenerator
     * @param Container               $container
     */
    public function __construct(
        FormInterface $form,
        Request $request,
        UserManagerInterface $userManager,
        MailerInterface $mailer,
        TokenGeneratorInterface $tokenGenerator,
        Container $container
    ) {
        parent::__construct($form, $request, $userManager, $mailer, $tokenGenerator);
        $this->container = $container;
    }

    /**
     * Override to add two stage register process with user email verification.
     *
     * @param bool $confirmation
     *
     * @return bool
     */
    public function process($confirmation = false)
    {
        $user = $this->createUser();
        $this->form->setData($user);

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($user, $confirmation);

                return true;
            }
        }

        return false;
    }

    /**
     * Override onSuccess to add two stage register process with user email verification.
     *
     * @param UserInterface $user
     * @param bool          $confirmation
     */
    protected function onSuccess(UserInterface $user, $confirmation)
    {
        $user->setEnabled(false);
        $user->setConfirmed(false);

        if ($confirmation) {
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $this->mailer->sendConfirmationEmailMessage($user);
        } else {
            $user->setEnabled(true);
            $user->setConfirmed(false);
        }

        $this->userManager->updateUser($user);

        //$token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        //$this->container->get('security.token_storage')->setToken($token);
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
