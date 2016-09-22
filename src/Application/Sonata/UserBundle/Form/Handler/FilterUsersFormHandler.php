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

use Application\Sonata\UserBundle\Form\Model\UserFilters;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class FilterUsersFormHandler
{
    protected $request;
    protected $form;

    /**
     * Construct handler for FilterUsersFormHandler.
     *
     * @param FormInterface $form
     * @param Request       $request
     * @param Container     $container
     */
    public function __construct(FormInterface $form, Request $request, Container $container)
    {
        $this->form = $form;
        $this->request = $request;
        $this->container = $container;
    }

    public function process()
    {
        $this->form->setData(new UserFilters());

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess();

                return true;
            }
        }

        return false;
    }

    protected function onSuccess()
    {
        return true;
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
