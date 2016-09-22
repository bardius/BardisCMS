<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\PageBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FormMailerService
{
    private $container;
    private $settings;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        // Get the settings from setting bundle
        $this->settings = $this->container->get('bardiscms_settings.load_settings')->loadSettings();
    }

    /**
     * Send email from ContactForm.
     *
     * @param array $emailData
     *
     * @return bool
     */
    public function sendContactFormEmail($emailData)
    {
        if (is_object($this->settings)) {
            $websiteTitle = $this->settings->getWebsiteTitle();
        } else {
            $websiteTitle = '';
        }

        // If data is valid send the email with the twig email template set in the views
        $message = \Swift_Message::newInstance()
            ->setSubject(
                'Contact form message from '.$websiteTitle.': '.$emailData['firstname'].' '.$emailData['surname'] - $emailData['email']
            )
            ->setFrom($this->settings->getEmailSender())
            ->setReplyTo($emailData['email'])
            ->setTo($this->settings->getEmailRecepient())
            ->setBody($this->container->get('twig')->render('PageBundle:Email:contactFormEmail.txt.twig', array(
                'sender' => $emailData['firstname'].' '.$emailData['surname'],
                'mailData' => $emailData['comment'],
            )));

        // Send the email with php swift mailer and catch errors
        try {
            $this->container->get('mailer')->send($message);

            return true;
        } catch (\Swift_TransportException $exception) {
            return false;
        }
    }
}
