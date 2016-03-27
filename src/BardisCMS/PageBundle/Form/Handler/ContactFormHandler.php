<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ContactFormHandler
{
    protected $request;
    protected $userManager;
    protected $form;
    private $container;
    private $settings;

    /**
     * Construct handler for ContactFormHandler
     *
     * @param FormInterface $form
     * @param Request $request
     * @param Container $container
     *
     */
    public function __construct(FormInterface $form, Request $request, Container $container)
    {
        $this->form = $form;
        $this->request = $request;
        $this->container = $container;

        // Get the settings from setting bundle
        $this->settings = $this->container->get('bardiscms_settings.load_settings')->loadSettings();
    }

    /**
     * Process the form for ContactForm
     *
     * @return boolean
     */
    public function process()
    {
        if ('POST' === $this->request->getMethod() && $this->request->request->has('contactform_form')) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $emailData = $this->form->getData();

                // Send the email and return false if error occurred on sending
                $contactFormEmailSent = $this->onSuccess($emailData);

                return $contactFormEmailSent;
            }
        }

        return false;
    }

    /**
     * onSuccess after processing the form for ContactForm
     *
     * @param $emailData
     *
     * @return boolean
     *
     */
    protected function onSuccess($emailData)
    {
        return $this->container->get('bardiscms_page.services.form_mailer')->sendContactFormEmail($emailData);
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
