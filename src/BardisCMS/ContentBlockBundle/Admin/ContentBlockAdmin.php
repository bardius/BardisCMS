<?php

/*
 * ContentBlock Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\ContentBlockBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use BardisCMS\ContentBlockBundle\Admin\Form\EventListener\AddContentTypeFieldSubscriber;

class ContentBlockAdmin extends AbstractAdmin {

    protected function configureFormFields(FormMapper $formMapper) {

        // Getting the container parameters set in the config file that exist
        $contentblockSettings = $this->getConfigurationPool()->getContainer()->getParameter('contentblock_settings');

        // Setting up the available content types and preffered choice
        $contentTypeChoices = $contentblockSettings['contenttypes'];
        reset($contentTypeChoices);
        $prefContentTypeChoice = key($contentTypeChoices);

        // Setting up the available content sizes and preffered choice
        $sizeclassChoices = $contentblockSettings['contentsizes'];
        reset($sizeclassChoices);
        $prefSizeclassChoice = key($sizeclassChoices);

        // Setting up the available media sizes
        $mediasizes = $contentblockSettings['mediasizes'];

        $subscriber = new AddContentTypeFieldSubscriber($formMapper->getFormBuilder()->getFormFactory(), $mediasizes);
        $formMapper->getFormBuilder()->addEventSubscriber($subscriber);

        $formMapper
                ->with('Content Block Details', array('collapsed' => false))
                ->add('title', 'text', array('label' => 'Content Block Title', 'required' => true))
                ->add('availability', 'choice', array('choices' => array('page' => 'One Page Only', 'global' => 'All Pages'),'preferred_choices' => array('0'), 'label' => 'Available to', 'required' => true))
                ->add('publishedState', 'choice', array('choices' => array('0' => 'Unpublished', '1' => 'Published'), 'preferred_choices' => array('1'), 'label' => 'Publish State', 'required' => true))
                ->add('showTitle', 'choice', array('choices' => array('0' => 'Hide Title', '1' => 'Show Title'), 'preferred_choices' => array('1'), 'label' => 'Title Display State', 'required' => true))
                ->add('ordering', 'hidden', array('attr' => array('class' => 'orderField'), 'label' => 'Content Block Ordering', 'required' => true))
                ->add('className', 'text', array('label' => 'CSS Class', 'required' => false))
                ->add('idName', 'text', array('label' => 'CSS Id', 'required' => false))
                ->add('sizeclass', 'choice', array('choices' => $sizeclassChoices, 'preferred_choices' => array($prefSizeclassChoice), 'label' => 'Content Block Width', 'required' => true))
                ->add('contentType', 'choice', array('choices' => $contentTypeChoices, 'preferred_choices' => array($prefContentTypeChoice), 'label' => 'Content Type', 'required' => true))
                ->setHelps(array(
                    'title' => 'Set the title of the content block'
                ))
                ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        // Getting the container parameters set in the config file that exist
        $contentblockSettings = $this->getConfigurationPool()->getContainer()->getParameter('contentblock_settings');

        // Setting up the available content types and preffered choice
        $contentTypeChoices = $contentblockSettings['contenttypes'];

        $datagridMapper
                ->add('title')
                ->add('availability', 'doctrine_orm_string', array(), 'choice', array('choices' => array('page' => 'Page Only', 'global' => 'Global')))
                ->add('publishState', 'doctrine_orm_string', array(), 'choice', array('choices' => array('0' => 'Unpublished', '1' => 'Published')))
                ->add('contentType', 'doctrine_orm_string', array(), 'choice', array('choices' => $contentTypeChoices))
                ->add('className')
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('title')
                ->addIdentifier('availability', null, array('sortable' => false, 'label' => 'Availability'))
                ->addIdentifier('publishStateAsString', null, array('sortable' => false, 'label' => 'Publish State'))
                ->addIdentifier('showTitleAsString', null, array('sortable' => false, 'label' => 'Title Visibility'))
                ->addIdentifier('className')
                ->addIdentifier('contentTypeAsString', null, array('sortable' => false, 'label' => 'Content Type'))
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(
                            'template' => 'ContentBlockBundle:Admin:edit.html.twig'
                        ),
                        'delete' => array(
                            'template' => 'ContentBlockBundle:Admin:delete.html.twig'
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
        $collection->add('edit', $this->getRouterIdParameter() . '/edit');
        $collection->add('delete', $this->getRouterIdParameter() . '/delete');
    }

}
