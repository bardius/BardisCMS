<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\PageBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SanitizeFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SUBMIT => 'preSubmitData');
    }

    public function preSubmitData(FormEvent $event)
    {
        // Sanitize data to avoid XSS attacks
        $data = $event->getData();

        $data = $this->sanitizeFormData($data);
        $event->setData($data);
    }

    public function sanitizeFormData($array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            $key = filter_var($key, FILTER_SANITIZE_STRING);

            if (is_array($value)) {
                $result[$key] = $this->sanitizeFormData($value);
            } else {
                $result[$key] = filter_var($value, FILTER_SANITIZE_STRING);
            }
        }

        return $result;
    }
}
