<?php

/*
 * Comment Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\CommentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use BardisCMS\CommentBundle\Admin\Form\EventListener\AddCommentTypeFieldSubscriber;

class CommentAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper) {

        $subscriber = new AddCommentTypeFieldSubscriber($formMapper->getFormBuilder()->getFormFactory());
        $formMapper->getFormBuilder()->addEventSubscriber($subscriber);

        // Getting the container parameters set in the config file that exist
        $commentSettings = $this->getConfigurationPool()->getContainer()->getParameter('comment_settings');

        // Setting up the available comment types
        $commentTypeChoice = $commentSettings['commenttypes'];
        reset($commentTypeChoice);
        $prefCommentTypeChoice = key($commentTypeChoice);

        $formMapper
                ->with('Comment Details', array('collapsed' => false))
                ->add('title', null, array('label' => 'Comment Title', 'required' => true))
                ->add('username', null, array('label' => 'Username / Name of the commentator', 'required' => true))
                ->add('comment', 'textarea', array('label' => 'Comment', 'required' => true))
                ->add('approved', 'choice', array('choices' => array('0' => 'Hide', '1' => 'Show'), 'preferred_choices' => array('1'), 'label' => 'Approve Comment', 'required' => true))
                ->add('created', 'date', array('widget' => 'single_text', 'format' => 'dd-MM-yyyy', 'attr' => array('class' => 'datepicker'), 'label' => 'Created Date', 'required' => true))
                ->add('commentType', 'choice', array('choices' => $commentTypeChoice, 'preferred_choices' => array($prefCommentTypeChoice), 'label' => 'Comment Type', 'required' => true))
                ->setHelps(array(
                    'title' => 'Set the title of the comment',
                    'commentType' => 'Set the page type that this applies to',
                    'username' => 'Set username/name of the userer that commented',
                    'created' => 'The comment creation date',
                    'comment' => 'The contents of the comment',
                    'approved' => 'Aproval Status'
                ))
                ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        // Getting the container parameters set in the config file that exist
        $commentSettings = $this->getConfigurationPool()->getContainer()->getParameter('comment_settings');

        $commentTypeChoice = $commentSettings['commenttypes'];

        $datagridMapper
                ->add('title')
                ->add('commentType', 'doctrine_orm_string', array(), 'choice', array('choices' => $commentTypeChoice))
                ->add('blogPost')
                ->add('created', 'doctrine_orm_date_range', array('input_type' => 'date'), 'sonata_type_date_range')
                ->add('username')
                ->add('approved', 'doctrine_orm_string', array(), 'choice', array('choices' => array('0' => 'Hide', '1' => 'Show')))
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('title')
                ->addIdentifier('commentTypeAsString', null, array('sortable' => false, 'label' => 'Comment Type'))
                ->addIdentifier('blogPost')
                ->addIdentifier('username')
                ->addIdentifier('created')
                ->addIdentifier('approvedAsString', null, array('sortable' => false, 'label' => 'Aproval Status'))
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'duplicate' => array(
                            'template' => 'CommentBundle:Admin:duplicate.html.twig'
                        ),
                        'edit' => array(
                            'template' => 'CommentBundle:Admin:edit.html.twig'
                        ),
                        'delete' => array(
                            'template' => 'CommentBundle:Admin:delete.html.twig'
                        )
                    )
                ))
        ;
    }

    public function validate(ErrorElement $errorElement, $object) {
        $errorElement
                ->with('title')
                ->assertLength(array('max' => 255))
                ->assertNotBlank()
                ->assertNotNull()
                ->end()
        ;
    }

    protected function configureRoutes(RouteCollection $collection) {
        $collection->add('duplicate', $this->getRouterIdParameter() . '/duplicate');
        $collection->add('edit', $this->getRouterIdParameter() . '/edit');
        $collection->add('delete', $this->getRouterIdParameter() . '/delete');
    }

}
