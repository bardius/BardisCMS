<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\SkeletonBundle\Admin;

use BardisCMS\SkeletonBundle\Entity\Skeleton as Skeleton;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Validator\ErrorElement;

class SkeletonAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        // Getting the container parameters set in the config file that exist
        $skeletonSettings = $this->getConfigurationPool()->getContainer()->getParameter('skeleton_settings');

        // Setting up the available page types and preferred choice
        $pagetypeChoices = $skeletonSettings['pagetypes'];
        reset($pagetypeChoices);
        $prefPagetypeChoice = key($pagetypeChoices);

        // Setting up the available media sizes and preferred choice
        $introMediaSizeChoices = $skeletonSettings['mediasizes'];
        reset($introMediaSizeChoices);
        $prefIntroMediaSizeChoice = key($introMediaSizeChoices);

        // Getting the user from container services that exist
        $loggedUser = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();

        // Using sonata admin to generate the edit page form and its fields
        $formMapper
                ->tab('Skeleton Page Essential Details')
                    ->with('Skeleton Page Essential Details', array('collapsed' => true))
                        ->add('title', null, array('attr' => array('class' => 'pageTitleField'), 'label' => 'Skeleton Page Title', 'required' => true))
                        ->add('publishState', 'choice', array('choices' => Skeleton::getPublishStateList(), 'preferred_choices' => array(Skeleton::STATUS_PREVIEW), 'label' => 'Publish Status', 'required' => true))
                        ->add('date', 'date', array('widget' => 'single_text', 'format' => 'dd-MM-yyyy', 'attr' => array('class' => 'datepicker'), 'label' => 'Publish Date', 'required' => true))
                        ->add('author', 'entity', array('class' => 'Application\Sonata\UserBundle\Entity\User', 'choice_label' => 'username', 'expanded' => false, 'multiple' => false, 'label' => 'Author', 'data' => $loggedUser->getUsername(), 'required' => true))
                        ->add('alias', null, array('attr' => array('class' => 'pageAliasField'), 'label' => 'Skeleton Page Alias', 'required' => false))
                        ->add('pagetype', 'choice', array('choices' => $pagetypeChoices, 'preferred_choices' => array($prefPagetypeChoice), 'label' => 'Skeleton Page Type', 'required' => true))
                        ->add('showPageTitle', 'choice', array('choices' => array('0' => 'Hide Title', '1' => 'Show Title'), 'preferred_choices' => array('1'), 'label' => 'Title Display', 'required' => true))
                        ->add('pageclass', null, array('label' => 'Skeleton Page CSS Class', 'required' => false))
                    ->setHelps(array(
                        'title' => 'Set the title of the Skeleton Page',
                        'publishState' => 'Set the publish status of the Skeleton Page',
                        'date' => 'Set the publishing date of the Skeleton Page',
                        'author' => 'The Author of the skeleton page',
                        'alias' => 'Set the URL alias of the Skeleton Page',
                        'pagetype' => 'Select the type of the Skeleton Page (Skeleton Page template)',
                        'pageclass' => 'Set the CSS class that wraps Skeleton Page',
                    ))
                    ->end()
                ->end()
                ->tab('Categories & Tags')
                    ->with('Categories & Tags', array('collapsed' => true))
                        ->add('categories', 'entity', array('class' => 'BardisCMS\CategoryBundle\Entity\Category', 'choice_label' => 'title', 'expanded' => true, 'multiple' => true, 'label' => 'Associated Categories', 'required' => false))
                        ->add('tags', 'entity', array('class' => 'BardisCMS\TagBundle\Entity\Tag', 'choice_label' => 'title', 'expanded' => true, 'multiple' => true, 'label' => 'Associated Tags', 'required' => false))
                    ->setHelps(array(
                        'tags' => 'Select the associated tags',
                        'categories' => 'Select the associated categories',
                    ))
                    ->end()
                ->end()
                ->tab('Skeleton Listing Page Intro')
                    ->with('Skeleton Listing Page Intro', array('collapsed' => true))
                        ->add('introtext', 'textarea', array('attr' => array('class' => 'tinymce', 'data-theme' => 'advanced'), 'label' => 'Intro Text/HTML', 'required' => false))
                        ->add('introimage', 'sonata_media_type', array('provider' => 'sonata.media.provider.image', 'context' => 'intro', 'attr' => array('class' => 'imagefield'), 'label' => 'Intro Image', 'required' => false))
                        ->add('introvideo', 'sonata_media_type', array('provider' => 'sonata.media.provider.vimeo', 'context' => 'intro', 'attr' => array('class' => 'videofield'), 'label' => 'Vimeo Video Id', 'required' => false))
                        ->add('pageOrder', null, array('label' => 'Intro Item Ordering in Homepage', 'required' => true))
                        ->add('introclass', null, array('label' => 'Intro Item CSS Class', 'required' => false))
                    ->setHelps(array(
                        'introtext' => 'Set the Text/HTML content to display for intro listing items',
                        'introimage' => 'Set the Image content to display for intro listing items',
                        'introvideo' => 'Set the video content to display for intro listing items',
                        'pageOrder' => 'Set the order of this Skeleton page intro for the homepage',
                        'introclass' => 'Set the CSS class that wraps content to display for intro listing items',
                    ))
                    ->end()
                ->end()
                ->tab('Skeleton Page Metatags Manual Override')
                    ->with('Skeleton Page Metatags Manual Override', array('collapsed' => true))
                        ->add('keywords', null, array('label' => 'Meta Keywords', 'required' => false))
                        ->add('description', null, array('label' => 'Meta Description', 'required' => false))
                    ->setHelps(array(
                        'keywords' => 'Set the keyword metadata of the page of leave empty to autogenerate',
                        'description' => 'Set the description metadata of the page of leave empty to autogenerate',
                    ))
                    ->end()
                ->end()
        ;

        // Check if it is a new entry. If it is hide the content block management
        if (!is_null($this->getSubject()->getId())) {
            // Setting up the available content block holders for each pagetype
            switch ($this->subject->getPagetype()) {
                case 'skeleton_article':
                    $formMapper
                            ->tab('Skeleton Page Contents')
                                ->with('Skeleton Page Contents', array('collapsed' => true))
                                    ->add('bannercontentblocks', 'contentblockcollection', array('attr' => array('class' => 'bannercontentblocks'), 'label' => 'Top Contents'))
                                    ->add('maincontentblocks', 'contentblockcollection', array('attr' => array('class' => 'maincontentblocks'), 'label' => 'Main Contents'))
                                    ->add('modalcontentblocks', 'contentblockcollection', array('attr' => array('class' => 'modalcontentblocks'), 'label' => 'Modal Windows Contents'))
                                ->setHelps(array(
                                    'bannercontentblocks' => 'Select the top contents in the order you want them to appear in the Skeleton Page',
                                    'maincontentblocks' => 'Select the main contents in the order you want them to appear in the Skeleton Page',
                                    'modalcontentblocks' => 'Select the contents in the order you want them to appear in the modal windows',
                                ))
                                ->end()
                            ->end()
                    ;
                    break;
                default:
                    $formMapper
                            ->tab('Skeleton Page Contents')
                                ->with('Skeleton Page Contents', array('collapsed' => true))
                                    ->add('maincontentblocks', 'contentblockcollection', array('attr' => array('class' => 'maincontentblocks'), 'label' => 'Main Contents'))
                                    ->add('modalcontentblocks', 'contentblockcollection', array('attr' => array('class' => 'modalcontentblocks'), 'label' => 'Modal Windows Contents'))
                                ->setHelps(array(
                                    'maincontentblocks' => 'Select the contents in the order you want them to appear in the Skeleton Page',
                                    'modalcontentblocks' => 'Select the contents in the order you want them to appear in the modal windows',
                                ))
                                ->end()
                            ->end()
                    ;
            }
        }
    }

    // Using sonata admin to generate the page listing grid filters
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

        // Getting the container parameters set in the config file that exist
        $skeletonSettings = $this->getConfigurationPool()->getContainer()->getParameter('skeleton_settings');

        // Setting up the available page types and preferred choice
        $pagetypeChoices = $skeletonSettings['pagetypes'];

        $datagridMapper
                ->add('title')
                ->add('publishState', 'doctrine_orm_string', array(), 'choice', array('choices' => Skeleton::getPublishStateList()))
                ->add('pagetype', 'doctrine_orm_string', array(), 'choice', array('choices' => $pagetypeChoices))
                ->add('categories')
                ->add('tags')
                ->add('author')
                ->add('date', 'doctrine_orm_date_range', array('input_type' => 'date'), 'sonata_type_date_range')
        ;
    }

    // Using sonata admin to generate th page listing grid and the grid item actions
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('id')
                ->addIdentifier('title')
                ->addIdentifier('alias')
                ->addIdentifier('publishStateAsString', null, array('sortable' => false, 'label' => 'Publish State'))
                ->addIdentifier('pagetypeAsString', null, array('sortable' => false, 'label' => 'Page Type'))
                ->addIdentifier('categories')
                ->addIdentifier('tags')
                ->addIdentifier('pageOrder')
                ->addIdentifier('author')
                ->addIdentifier('date')
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'duplicate' => array(
                            'template' => 'SkeletonBundle:Admin:duplicate.html.twig',
                        ),
                        'edit' => array(
                            'template' => 'SkeletonBundle:Admin:edit.html.twig',
                        ),
                        'delete' => array(
                            'template' => 'SkeletonBundle:Admin:delete.html.twig',
                        ),
                    ),
                ))
        ;
    }

    // Adding the validation rules for the page form
    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
                ->with('title')
                ->assertLength(array('max' => 255))
                ->assertNotBlank()
                ->assertNotNull()
                ->end()
                ->with('author')
                ->assertLength(array('max' => 255))
                ->assertNotBlank()
                ->assertNotNull()
                ->end()
        ;
    }

    // Adding the route names for the page actions
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('duplicate', $this->getRouterIdParameter().'/duplicate');
        $collection->add('edit', $this->getRouterIdParameter().'/edit');
        $collection->add('delete', $this->getRouterIdParameter().'/delete');
    }
}
