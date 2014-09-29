<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PageAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper) {

        // Getting the container parameters set in the config file that exist after the dependency injection
        $pageSettings = $this->getConfigurationPool()->getContainer()->getParameter('page_settings');

        // Setting up the available page types and preffered choice
        $pagetypeChoices = $pageSettings['pagetypes'];
        reset($pagetypeChoices);
        $prefPagetypeChoice = key($pagetypeChoices);

        // Setting up the available media sizes and preffered choice
        $introMediaSizeChoices = $pageSettings['mediasizes'];
        reset($introMediaSizeChoices);
        $prefIntroMediaSizeChoice = key($introMediaSizeChoices);

        //getting the container services that exist
        $loggedUser = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();

        // using sonata admin to generate the edit page form and its fields
        $formMapper
            ->with('Page Essential Details', array('collapsed' => false))
            ->add('title', null, array('attr' => array('class' => 'pageTitleField'), 'label' => 'Page Title', 'required' => true))
            ->add('publishState', 'choice', array('choices' => array('0' => 'Unpublished', '1' => 'Published', '2' => 'Preview'), 'preferred_choices' => array('2'), 'label' => 'Publish State', 'required' => true))
            ->add('date', 'date', array('widget' => 'single_text', 'format' => 'dd-MM-yyyy', 'attr' => array('class' => 'datepicker'), 'label' => 'Publish Date', 'required' => true))
            ->add('author', 'entity', array('class' => 'Application\Sonata\UserBundle\Entity\User', 'property' => 'username', 'expanded' => false, 'multiple' => false, 'label' => 'Author', 'data' => $loggedUser->getId(), 'required' => true))
            ->add('alias', null, array('attr' => array('class' => 'pageAliasField'), 'label' => 'Page Alias', 'required' => false))
            ->add('pagetype', 'choice', array('choices' => $pagetypeChoices, 'preferred_choices' => array($prefPagetypeChoice), 'label' => 'Page Type', 'required' => true))
            ->add('showPageTitle', 'choice', array('choices' => array('0' => 'Hide Title', '1' => 'Show Title'), 'preferred_choices' => array('1'), 'label' => 'Title Display State', 'required' => true))
            ->add('pageclass', null, array('label' => 'Page CSS Class', 'required' => false))
            ->add('bgimage', 'sonata_media_type', array('provider' => 'sonata.media.provider.image', 'context' => 'bgimage', 'attr' => array('class' => 'imagefield'), 'label' => 'Top Banner Image', 'required' => false))
            ->setHelps(array(
                'title' => 'Set the title',
                'publishState' => 'Set the publish',
                'date' => 'Set the publishing date',
                'author' => 'Select the Author',
                'alias' => 'Set the URL alias',
                'pagetype' => 'Select the type of the page (page template)',
                'pageclass' => 'Set the CSS class that wraps the page',
                'bgimage' => 'Set the Top Banner Image of the page'
            ))
            ->end()
            ->with('Categories & Tags', array('collapsed' => true))
            ->add('categories', 'entity', array('class' => 'BardisCMS\CategoryBundle\Entity\Category', 'property' => 'title', 'expanded' => true, 'multiple' => true, 'label' => 'Associated Categories', 'required' => false))
            ->add('tags', 'entity', array('class' => 'BardisCMS\TagBundle\Entity\Tag', 'property' => 'title', 'expanded' => true, 'multiple' => true, 'label' => 'Associated Tags', 'required' => false))
            ->setHelps(array(
                'tags' => 'Select the associated tags',
                'categories' => 'Select the associated categories'
            ))
            ->with('Homepage & Listing Page Intro', array('collapsed' => true))
            ->add('introtext', 'textarea', array('attr' => array('class' => 'tinymce', 'data-theme' => 'advanced'), 'label' => 'Intro Text/HTML', 'required' => false))
            ->add('introimage', 'sonata_media_type', array('provider' => 'sonata.media.provider.image', 'context' => 'intro', 'attr' => array('class' => 'imagefield'), 'label' => 'Intro Image', 'required' => false))
            ->add('intromediasize', 'choice', array('choices' => $introMediaSizeChoices, 'preferred_choices' => array($prefIntroMediaSizeChoice), 'label' => 'Media Size', 'required' => true))
            ->add('pageOrder', null, array('label' => 'Intro Item Ordering in Homepage', 'required' => true))
            ->add('introclass', null, array('label' => 'Intro Item CSS Class', 'required' => false))
            ->setHelps(array(
                'introtext' => 'Set the Text/HTML content to display for category listing items',
                'introimage' => 'Set the Image content to display for category listing items',
                'pageOrder' => 'Set the order of this page for the homepage',
                'introclass' => 'Set the CSS class that wraps content to display for category listing items'
            ))
            ->end()
            ->with('Page Metatags Manual Override', array('collapsed' => true))
            ->add('keywords', null, array('label' => 'Meta Keywords', 'required' => false))
            ->add('description', null, array('label' => 'Meta Description', 'required' => false))
            ->setHelps(array(
                'keywords' => 'Set the keyword metadata of the page of leave empty to autogenerate',
                'description' => 'Set the description metadata of the page of leave empty to autogenerate'
            ))
            ->end()
        ;

        // Check if it is a new entry. If it is hide the content block management
        if (!is_null($this->getSubject()->getId())) {
            //setting up the available content block holders for each pagetype
            switch ($this->subject->getPagetype()) {
                case 'one_columned':
                    $formMapper
                        ->with('Page Contents', array('collapsed' => true))
                        ->add('bannercontentblocks', 'contentblockcollection', array('attr' => array('class' => 'bannercontentblocks'), 'label' => 'Top Banner Contents'))
                        ->add('maincontentblocks', 'contentblockcollection', array('attr' => array('class' => 'maincontentblocks'), 'label' => 'Page Contents'))
                        ->add('modalcontentblocks', 'contentblockcollection', array('attr' => array('class' => 'modalcontentblocks'), 'label' => 'Modal Windows Contents'))
                        ->setHelps(array(
                            'bannercontentblocks' => 'Enter the contents for the top banner',
                            'maincontentblocks' => 'Enter the contents for the page',
                            'modalcontentblocks' => 'Enter the contents for the modal windows'
                        ))
                        ->end()
                    ;
                    break;
                case 'two_columned':
                case 'contact':
                    $formMapper
                        ->with('Page Contents', array('collapsed' => true))
                        ->add('bannercontentblocks', 'contentblockcollection', array('attr' => array('class' => 'bannercontentblocks'), 'label' => 'Top Banner Contents'))
                        ->add('maincontentblocks', 'contentblockcollection', array('attr' => array('class' => 'maincontentblocks'), 'label' => 'Left Column Contents'))
                        ->add('secondarycontentblocks', 'contentblockcollection', array('attr' => array('class' => 'secondarycontentblocks'), 'label' => 'Right Column Contents'))
                        ->add('modalcontentblocks', 'contentblockcollection', array('attr' => array('class' => 'modalcontentblocks'), 'label' => 'Modal Windows Contents'))
                        ->setHelps(array(
                            'bannercontentblocks' => 'Enter the contents for the top banner',
                            'maincontentblocks' => 'Enter the contents for the left column',
                            'secondarycontentblocks' => 'Enter the contents for the right column',
                            'modalcontentblocks' => 'Enter the contents for the modal windows'
                        ))
                        ->end()
                    ;
                    break;
                case 'three_columned':
                    $formMapper
                        ->with('Page Contents', array('collapsed' => true))
                        ->add('bannercontentblocks', 'contentblockcollection', array('attr' => array('class' => 'bannercontentblocks'), 'label' => 'Top Banner Contents'))
                        ->add('maincontentblocks', 'contentblockcollection', array('attr' => array('class' => 'maincontentblocks'), 'label' => 'Left Column Contents'))
                        ->add('secondarycontentblocks', 'contentblockcollection', array('attr' => array('class' => 'secondarycontentblocks'), 'label' => 'Middle Column Contents'))
                        ->add('extracontentblocks', 'contentblockcollection', array('attr' => array('class' => 'extracontentblocks'), 'label' => 'Right Column Contents'))
                        ->add('modalcontentblocks', 'contentblockcollection', array('attr' => array('class' => 'modalcontentblocks'), 'label' => 'Modal Windows Contents'))
                        ->setHelps(array(
                            'bannercontentblocks' => 'Enter the contents for the top banner',
                            'maincontentblocks' => 'Enter the contents for the left column',
                            'secondarycontentblocks' => 'Enter the contents for the middle column',
                            'extracontentblocks' => 'Enter the contents for the right column',
                            'modalcontentblocks' => 'Enter the contents for the modal windows'
                        ))
                        ->end()
                    ;
                    break;
                case 'category_page':
                case 'page_tag_list':
                case 'sitemap':
                case 'homepage':
                    $formMapper
                        ->with('Page Contents', array('collapsed' => true))
                        ->add('bannercontentblocks', 'contentblockcollection', array('attr' => array('class' => 'bannercontentblocks'), 'label' => 'Top Banner Contents'))
                        ->add('maincontentblocks', 'contentblockcollection', array('attr' => array('class' => 'maincontentblocks'), 'label' => 'Contents Below Item Listing'))
                        ->add('modalcontentblocks', 'contentblockcollection', array('attr' => array('class' => 'modalcontentblocks'), 'label' => 'Modal Windows Contents'))
                        ->setHelps(array(
                            'bannercontentblocks' => 'Enter the contents for the top banner',
                            'maincontentblocks' => 'Enter the contents that appear below the items list',
                            'modalcontentblocks' => 'Enter the contents for the modal windows'
                        ))
                        ->end()
                    ;
                    break;
                default:
                    $formMapper
                        ->with('Page Contents', array('collapsed' => true))
                        ->add('maincontentblocks', 'contentblockcollection', array('attr' => array('class' => 'maincontentblocks'), 'label' => 'Page Contents'))
                        ->add('modalcontentblocks', 'contentblockcollection', array('attr' => array('class' => 'modalcontentblocks'), 'label' => 'Modal Windows Contents'))
                        ->setHelps(array(
                            'maincontentblocks' => 'Enter the contents for the page',
                            'modalcontentblocks' => 'Enter the contents for the modal windows'
                        ))
                        ->end()
                    ;
            }
        }
    }

    // Using sonata admin to generate th page listing grid filters
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {

        // Getting the container parameters set in the config file that exist after the dependency injection
        $pageSettings = $this->getConfigurationPool()->getContainer()->getParameter('page_settings');

        // Setting up the available page types and preffered choice
        $pagetypeChoices = $pageSettings['pagetypes'];

        $datagridMapper
            ->add('title')
            ->add('publishState', 'doctrine_orm_string', array(), 'choice', array('choices' => array('0' => 'Unpublished', '1' => 'Published', '2' => 'Preview')))
            ->add('pagetype', 'doctrine_orm_string', array(), 'choice', array('choices' => $pagetypeChoices))
            ->add('categories')
            ->add('tags')
            ->add('author')
            ->add('date', 'doctrine_orm_date_range', array('input_type' => 'date'), 'sonata_type_date_range')
        ;
    }

    // Using sonata admin to generate th page listing grid and the grid item actions
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
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
                        'template' => 'PageBundle:Admin:duplicate.html.twig'
                    ),
                    'edit' => array(
                        'template' => 'PageBundle:Admin:edit.html.twig'
                    ),
                    'delete' => array(
                        'template' => 'PageBundle:Admin:delete.html.twig'
                    )
                )
            ))
        ;
    }

    // Adding the validation rules for the page form
    public function validate(ErrorElement $errorElement, $object) {
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
    protected function configureRoutes(RouteCollection $collection) {
        $collection->add('duplicate', $this->getRouterIdParameter() . '/duplicate');
        $collection->add('edit', $this->getRouterIdParameter() . '/edit');
        $collection->add('delete', $this->getRouterIdParameter() . '/delete');
        $collection->add('clearCache', 'clearcache');
        $collection->add('clearCacheProd', 'clearcacheprod');
        $collection->add('clearHTTPCache', 'clearhttpcache');
    }

}