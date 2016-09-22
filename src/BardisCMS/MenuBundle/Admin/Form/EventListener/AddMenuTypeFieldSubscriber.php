<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\MenuBundle\Admin\Form\EventListener;

use BardisCMS\MenuBundle\Entity\Menu;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;

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

        // check the menu type and present the required field to enter page id
        switch ($data->getMenuType()) {
            case Menu::TYPE_BLOG:
                $form->add($this->factory->createNamed('blog', 'entity', null, array(
                    'auto_initialize' => false,
                    'class' => 'BardisCMS\BlogBundle\Entity\Blog',
                    'choice_label' => 'title',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Select Linked Blog Page',
                    'attr' => array(
                        'class' => 'autoCompleteItems autoCompleteBlogs',
                        'data-sonata-select2' => 'false',
                    ),
                    'required' => false,
                )));
                $form->add($this->factory->createNamed('menuUrlExtras', 'text', null, array(
                    'auto_initialize' => false,
                    'label' => 'Extra URL Params',
                    'required' => false,
                )));
                break;
            case Menu::TYPE_PAGE:
                $form->add($this->factory->createNamed('page', 'entity', null, array(
                    'auto_initialize' => false,
                    'class' => 'BardisCMS\PageBundle\Entity\Page',
                    'choice_label' => 'title',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Select Linked Page',
                    'attr' => array(
                        'class' => 'autoCompleteItems autoCompletePages',
                        'data-sonata-select2' => 'false',
                    ),
                    'required' => false,
                )));
                $form->add($this->factory->createNamed('menuUrlExtras', 'text', null, array(
                    'auto_initialize' => false,
                    'label' => 'Extra URL Params',
                    'required' => false,
                )));
                break;
            case Menu::TYPE_EXTERNAL_URL:
                $form->add($this->factory->createNamed('externalUrl', 'text', null, array(
                    'auto_initialize' => false,
                    'label' => 'External URL',
                    'required' => false,
                )));
                break;
            case Menu::TYPE_INTERNAL_URL:
                $form->add($this->factory->createNamed('externalUrl', 'text', null, array(
                    'auto_initialize' => false,
                    'label' => 'Page URL',
                    'required' => false,
                )));
                break;
            default:
        }
    }
}
