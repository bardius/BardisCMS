<?php

/*
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\MenuBundle\Admin\Form\EventListener;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class AddMenuTypeFieldSubscriber implements EventSubscriberInterface
{
   private $factory;

    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. We're only concerned with when
        // setData is called with an actual Entity object in it (whether new,
        // or fetched with Doctrine). This if statement let's us skip right
        // over the null condition.
        if (null === $data) {
            return;
        }
	
        // check the menu type and presend the required field to enter page id
        switch ($data->getMenuType()) {
            case 'Blog':
                $form->add($this->factory->createNamed('blog', 'entity', null, array('auto_initialize' => false, 'class' => 'BardisCMS\BlogBundle\Entity\Blog', 'property' => 'title', 'expanded' => false, 'multiple' => false, 'label' => 'Select Linked Blog Page', 'attr' => array('class' => 'autoCompleteItems autoCompleteBlogs'), 'required' => false)));
                $form->add($this->factory->createNamed('menuUrlExtras', 'text', null, array('auto_initialize' => false, 'label' => 'Extra URL Params', 'required' => false)));
                break;
            case 'Page':
                $form->add($this->factory->createNamed('page', 'entity', null, array('auto_initialize' => false, 'class' => 'BardisCMS\PageBundle\Entity\Page', 'property' => 'title', 'expanded' => false, 'multiple' => false, 'label' => 'Select Linked Page', 'attr' => array('class' => 'autoCompleteItems autoCompletePages'), 'required' => false)));
                $form->add($this->factory->createNamed('menuUrlExtras', 'text', null, array('auto_initialize' => false, 'label' => 'Extra URL Params', 'required' => false)));
                break;
            case 'http':
                $form->add($this->factory->createNamed('externalUrl', 'text', null, array('auto_initialize' => false, 'label' => 'External URL', 'required' => false)));
                break;
            case 'url':
                $form->add($this->factory->createNamed('externalUrl', 'text', null, array('auto_initialize' => false, 'label' => 'Page URL', 'required' => false)));
                break;
            default:
        }
    }
}
