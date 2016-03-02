<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, Container $container)
    {
        $this->form = $form;
        $this->request = $request;
        $this->userManager = $userManager;
        $this->container = $container;
    }

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
