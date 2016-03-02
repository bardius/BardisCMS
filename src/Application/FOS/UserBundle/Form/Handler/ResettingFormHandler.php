<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\FOS\UserBundle\Form\Handler;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Form\Handler\ResettingFormHandler as BaseHandler;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ResettingFormHandler extends BaseHandler
{
    private $container;

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
