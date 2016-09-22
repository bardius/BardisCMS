<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\CommentBundle\Admin\Form\EventListener;

use BardisCMS\CommentBundle\Entity\Comment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;

class AddCommentTypeFieldSubscriber implements EventSubscriberInterface
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

        // Check the comment type and presend the required field to enter page/blog post id
        switch ($data->getCommentType()) {

            case Comment::TYPE_BLOG:
                $form->add($this->factory->createNamed('blogPost', 'entity', null, array(
                    'auto_initialize' => false,
                    'class' => 'BardisCMS\BlogBundle\Entity\Blog',
                    'choice_label' => 'title',
                    'expanded' => false,
                    'multiple' => false,
                    'label' => 'Select Linked Blog Post',
                    'attr' => array(
                        'class' => 'autoCompleteItems autoCompleteBlogs',
                        'data-sonata-select2' => 'false',
                    ), 'required' => false,
                )));
                break;

            default:
        }
    }
}
