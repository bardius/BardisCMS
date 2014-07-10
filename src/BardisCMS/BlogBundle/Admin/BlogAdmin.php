<?php 
/*
 * Blog Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;


class BlogAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {        
        // Getting the container parameters set in the config file that exist
        $blogSettings = $this->getConfigurationPool()->getContainer()->getParameter('blog_settings');
        
        // Setting up the available page types and preffered choice
        $pagetypeChoices            = $blogSettings['pagetypes'];
        reset($pagetypeChoices);
        $prefPagetypeChoice         = key($pagetypeChoices);

        // Setting up the available media sizes and preffered choice
        //$introMediaSizeChoices      = $blogSettings['mediasizes'];
        //reset($introMediaSizeChoices);
        //$prefIntroMediaSizeChoice   = key($introMediaSizeChoices);
        
        // Getting the container services that exist and then the user roles
        $loggedUser     = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
        $loggedUserRole = $loggedUser->getRoles();
        
        $formMapper
            ->with('Blog Page Essential Details', array('collapsed' => false))
                ->add('title', null, array('attr' => array('class' => 'pageTitleField'), 'label' => 'Page Title', 'required' => true))
                ->add('publishState', 'choice', array('choices' => array('0' => 'Unpublished', '1' => 'Published', '2' => 'Preview'), 'preferred_choices' => array('2'), 'label' => 'Publish Status', 'required' => true))
                ->add('date', 'date', array('widget' => 'single_text', 'format' => 'dd-MM-yyyy', 'attr' => array('class' => 'datepicker'), 'label' => 'Publish Date', 'required' => true))
				->add('author',  'entity', array('class' => 'Application\Sonata\UserBundle\Entity\User', 'property' => 'username', 'expanded' => false, 'multiple' => false, 'label' => 'Author', 'data' => $loggedUser->getUsername(), 'required' => true))
                ->add('alias', null, array('attr' => array('class' => 'pageAliasField'), 'label' => 'Page Alias', 'required' => false))
                ->add('pagetype', 'choice', array('choices' => $pagetypeChoices, 'preferred_choices' => array($prefPagetypeChoice), 'label' => 'Page Type', 'required' => true))              
                ->add('showPageTitle', 'choice', array('choices' => array('0' => 'Hide Title', '1' => 'Show Title'), 'preferred_choices' => array('1'), 'label' => 'Title Display', 'required' => true))
                ->add('bgimage', 'sonata_media_type', array( 'provider' => 'sonata.media.provider.image', 'context' => 'bgimage', 'attr' => array( 'class' => 'imagefield'), 'label' => 'Top Banner Image', 'required' => false))
                ->add('pageclass', null, array('label' => 'Page CSS Class', 'required' => false))
                ->setHelps(array(
                    'title'         => 'Set the title',
                    'publishState'  => 'Set the publish status',
                    'date'          => 'Set the publishing date',
                    'author'        => 'Select the Author',
                    'alias'         => 'Set the URL alias',
                    'pagetype'      => 'Select the type of the Page (Blog Page template)',
                    'pageclass'     => 'Set the CSS class that wraps the Page',
                    'bgimage'       => 'Set the Top Banner Image of the page'
                ))
            ->end()
            ->with('Categories & Tags', array('collapsed' => true))
                ->add('categories', 'entity', array('class' => 'BardisCMS\CategoryBundle\Entity\Category', 'property' => 'title', 'expanded' => true, 'multiple' => true, 'label' => 'Associated Categories', 'required' => false))
                ->add('tags', 'entity', array('class' => 'BardisCMS\TagBundle\Entity\Tag', 'property' => 'title', 'expanded' => true, 'multiple' => true, 'label' => 'Associated Tags', 'required' => false))                
                ->setHelps(array(
                    'tags'          => 'Select the associated tags',
                    'categories'    => 'Select the associated categories'
                ))
            ->with('Homepage & Listing Page Intro', array('collapsed' => true))
                ->add('introtext', 'textarea', array('attr' => array('class' => 'tinymce', 'data-theme' => 'advanced'), 'label' => 'Intro Text/HTML', 'required' => false))
                ->add('introimage', 'sonata_media_type', array( 'provider' => 'sonata.media.provider.image', 'context' => 'intro', 'attr' => array( 'class' => 'imagefield'), 'label' => 'Intro Image', 'required' => false))
                //->add('intromediasize', 'choice', array('choices' => $introMediaSizeChoices, 'preferred_choices' => array($prefIntroMediaSizeChoice), 'label' => 'Media Size', 'required' => true))
                ->add('introvideo', 'sonata_media_type', array( 'provider' => 'sonata.media.provider.youtube', 'context' => 'intro', 'attr' => array( 'class' => 'videofield'), 'label' => 'YouTube Video Id', 'required' => false))
                ->add('pageOrder', null, array('label' => 'Intro Item Ordering in Homepage', 'required' => true))
                ->add('introclass', null, array('label' => 'Intro Item CSS Class', 'required' => false))
                ->setHelps(array(
                    'introtext'     => 'Set the Text/HTML content to display for intro listing items',
                    'introimage'    => 'Set the Image content to display for intro listing items',
                    'introvideo'    => 'Set the video content to display for intro listing items',
                    'pageOrder'     => 'Set the order of this page intro for the homepage',
                    'introclass'    => 'Set the CSS class that wraps content to display for intro listing items'
                ))
            ->end()
            ->with('Page Metatags Manual Override', array('collapsed' => true))
                ->add('keywords', null, array('label' => 'Meta Keywords', 'required' => false))
                ->add('description', null, array('label' => 'Meta Description', 'required' => false))
                ->setHelps(array(
                    'keywords'      => 'Set the keyword metadata of the page of leave empty to autogenerate',
                    'description'   => 'Set the description metadata of the page of leave empty to autogenerate'
                ))
            ->end()
        ;
        
        // Check if it is a new entry. If it is hide the content block management
        if(!is_null($this->getSubject()->getId()))
        {
            // Setting up the available content block holders for each pagetype
            switch ($this->subject->getPagetype()) {
                case 'blog_article':
                $formMapper  
                        ->with('Page Contents', array('collapsed' => true))
                            ->add('bannercontentblocks','contentblockcollection', array('attr' => array('class' => 'bannercontentblocks'), 'label' => 'Top Banner Contents'))
                            ->add('maincontentblocks','contentblockcollection', array('attr' => array('class' => 'maincontentblocks'), 'label' => 'Main Blog Contents')) 
                            ->add('modalcontentblocks','contentblockcollection', array('attr' => array('class' => 'modalcontentblocks'), 'label' => 'Modal Windows Contents')) 
                            ->setHelps(array(
                                'bannercontentblocks'   => 'Add an image or a carousel for the top banner',
                                'maincontentblocks'     => 'Select the main contents in the order you want them to appear in the page',
                                'modalcontentblocks'    => 'Select the contents in the order you want them to appear in the modal windows'
                            )) 
                        ->end() 
                    ;
                    break;
                default:
                $formMapper  
                        ->with('Page Contents', array('collapsed' => true))
                            ->add('maincontentblocks','contentblockcollection', array('attr' => array('class' => 'maincontentblocks'), 'label' => 'Contents above the Blog List')) 
                            ->add('extracontentblocks','contentblockcollection', array('attr' => array('class' => 'extracontentblocks'), 'label' => 'Contents bellow the Blog List'))
                            ->add('modalcontentblocks','contentblockcollection', array('attr' => array('class' => 'modalcontentblocks'), 'label' => 'Modal Windows Contents')) 
                            ->setHelps(array(
                                'maincontentblocks'     => 'Select the contents in the order you want them to appear above the blog list',
                                'extracontentblocks'   => 'Select the contents in the order you want them to appear bellow the blog list',
                                'modalcontentblocks'    => 'Select the contents in the order you want them to appear in the modal windows'
                            )) 
                        ->end() 
                    ;
            } 
        }  
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        // Getting the container parameters set in the config file that exist
        $blogSettings = $this->getConfigurationPool()->getContainer()->getParameter('blog_settings');
        
        // Setting up the available page types and preffered choice
        $pagetypeChoices = $blogSettings['pagetypes'];
        
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

    protected function configureListFields(ListMapper $listMapper)
    {
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
                            'template' => 'BlogBundle:Admin:duplicate.html.twig'
                        ),
                        'edit' => array(
                            'template' => 'BlogBundle:Admin:edit.html.twig'
                        ),
                        'delete' => array(
                            'template' => 'BlogBundle:Admin:delete.html.twig'
                        )
                    )
                ))
        ;
    }

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
    
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('duplicate', $this->getRouterIdParameter().'/duplicate');
        $collection->add('edit', $this->getRouterIdParameter().'/edit');
        $collection->add('delete', $this->getRouterIdParameter().'/delete');
    }

}
