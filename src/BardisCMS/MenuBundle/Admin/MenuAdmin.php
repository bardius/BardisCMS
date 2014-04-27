<?php 

/*
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */


namespace BardisCMS\MenuBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Form\Type;
use BardisCMS\MenuBundle\Admin\Form\EventListener\AddMenuTypeFieldSubscriber;
use Symfony\Component\Form\FormBuilderInterface;

use Doctrine\ORM\EntityManager;
use BardisCMS\PageBundle\Entity\Page;


class MenuAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {        
        
        $subscriber = new AddMenuTypeFieldSubscriber($formMapper->getFormBuilder()->getFormFactory());
        $formMapper->getFormBuilder()->addEventSubscriber($subscriber);
        
        // Getting the container parameters set in the config file that exist
        $menuSettings = $this->getConfigurationPool()->getContainer()->getParameter('menu_settings');
        
        // Setting up the available actions
        $actionsChoice              = $menuSettings['actions'];
        reset($actionsChoice);
        $prefActionsChoice          = key($actionsChoice);
        
        // Setting up the available menu types
        $menuTypeChoice             = $menuSettings['menutypes'];
        reset($menuTypeChoice);
        $prefMenuTypeChoice         = key($menuTypeChoice);
        
        // Setting up the available menu groups
        $menuGroupsChoice           = $menuSettings['menugroups'];
        reset($menuGroupsChoice);
        $prefMenuGroupsChoice       = key($menuGroupsChoice);
        
        // Setting up the available page types and preffered choice
        $accessLevelChoices         = $menuSettings['accessLevel'];
        reset($accessLevelChoices);
        $prefAccessLevelChoices     = key($accessLevelChoices);
        
        // Setting up the available publish states and preffered choice
        $publishStateChoices        = $menuSettings['publishState'];
        reset($publishStateChoices);
        $prefPublishStateChoices     = key($publishStateChoices);
	
        $menus = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository('MenuBundle:Menu')
            ->findAll();
        
        $menusChoice['-']   = 'Hide From Menu';
        $menusChoice['0']   = 'Menu Root';
        
        foreach($menus as $menu)
        {
            $menusChoice[$menu->getId()] = $menu->getTitle().' ('.$menu->getMenuGroup().')';
        }
        
        $formMapper
            ->with('Menu Item Essential Details', array('collapsed' => true))
                ->add('title', null, array('label' => 'Title', 'required' => true))
                ->add('menuType', 'choice', array('choices' => $menuTypeChoice, 'preferred_choices' => array($prefMenuTypeChoice), 'label' => 'Menu Item Type', 'required' => true))
                ->add('route', 'choice', array('choices' => $actionsChoice, 'preferred_choices' => array($prefActionsChoice), 'label' => 'Link Action', 'required' => true))
                ->setHelps(array(
                    'title'         => 'Set the title of the menu item (link copy text)',
                    'menuType'      => 'Set the type of the menu item linked page',
                    'route'         => 'Select the action of the menu item'
                ))
            ->end()
            ->with('Menu Item Taxonomy', array('collapsed' => true))
                ->add('menuGroup', 'choice', array('choices'   => $menuGroupsChoice, 'preferred_choices' => array($prefMenuGroupsChoice), 'label' => 'Menu Group', 'required' => true))
                ->add('parent', 'choice', array('choices' =>  $menusChoice, 'attr' => array('class' => 'autoCompleteItems autoCompleteMenus', 'data-sonata-select2' => 'false'), 'label' => 'Parent Menu Item', 'required' => false))
                ->add('ordering', null, array('label' => 'Menu Item Order', 'required' => true))
                ->setHelps(array(
                    'menuGroup'     => 'Set the menu group this menu item belongs to',
                    'parent'        => 'Select the parent menu item',
                    'ordering'      => 'Set the order of the menu item in accordance to the other menu items of the same menu level'
                ))
            ->end()
            ->with('Menu Item Access Control', array('collapsed' => true))
                ->add('accessLevel', 'choice', array('choices' => $accessLevelChoices, 'preferred_choices' => array($prefAccessLevelChoices), 'label' => 'Access Level', 'required' => true))
                ->add('publishState', 'choice', array('choices' => $publishStateChoices, 'preferred_choices' => array($prefPublishStateChoices), 'label' => 'Publish State', 'required' => true))
                ->setHelps(array(
                    'accessLevel' 	=> 'Set the minimum access level the item is visible to',
                    'publishState' 	=> 'Set the publish state of this menu item'
                ))
            ->end()
            ->with('Menu Item Optional Details', array('collapsed' => true))
                ->add('menuImage', 'sonata_media_type', array( 'provider' => 'sonata.media.provider.image', 'context' => 'icons', 'attr' => array( 'class' => 'imagefield'), 'label' => 'Menu Icon Image', 'required' => false))
                ->setHelps(array(
                    'menuImage' 	=> 'Set an image as Menu Icon'
                ))           
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        
        // Getting the container parameters set in the config file that exist
        $menuSettings = $this->getConfigurationPool()->getContainer()->getParameter('menu_settings');

        $menuTypeChoice             = $menuSettings['menutypes'];
        $menuGroupsChoice           = $menuSettings['menugroups'];
        $accessLevelChoices         = $menuSettings['accessLevel'];
        $publishStateChoices        = $menuSettings['publishState'];
        
        $datagridMapper
            ->add('title')
            ->add('menuGroup', 'doctrine_orm_string', array(), 'choice', array('choices' => $menuGroupsChoice))
            ->add('menuType', 'doctrine_orm_string', array(), 'choice', array('choices' => $menuTypeChoice))
            ->add('page')
            ->add('blog')
            ->add('publishState', 'doctrine_orm_string', array(), 'choice', array('choices' => $publishStateChoices))
            ->add('accessLevel', 'doctrine_orm_string', array(), 'choice', array('choices' => $accessLevelChoices))  
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {      
        $listMapper
            ->addIdentifier('title')
            ->addIdentifier('menuGroup')
            ->addIdentifier('menuTypeAsString', null, array('sortable' => false, 'label' => 'Menu Item Type'))
            ->addIdentifier('page')
            ->addIdentifier('blog')
            ->addIdentifier('accessLevelAsString', null, array('sortable' => false, 'label' => 'Access Level'))
            ->addIdentifier('ordering')  
            ->addIdentifier('publishStateAsString', null, array('sortable' => false, 'label' => 'Publish State'))
            ->add('_action', 'actions', array( 
                    'actions' => array(  
                        'duplicate' => array(
                            'template' => 'MenuBundle:Admin:duplicate.html.twig'
                        ),
                        'edit' => array(
                            'template' => 'MenuBundle:Admin:edit.html.twig'
                        ),
                        'delete' => array(
                            'template' => 'MenuBundle:Admin:delete.html.twig'
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
