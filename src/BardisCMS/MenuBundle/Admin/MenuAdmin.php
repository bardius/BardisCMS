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
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use BardisCMS\MenuBundle\Admin\Form\EventListener\AddMenuTypeFieldSubscriber;

use BardisCMS\MenuBundle\Entity\Menu as Menu;

class MenuAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper) {

        $subscriber = new AddMenuTypeFieldSubscriber($formMapper->getFormBuilder()->getFormFactory());
        $formMapper->getFormBuilder()->addEventSubscriber($subscriber);

        // Getting the container parameters set in the config file that exist
        $menuSettings = $this->getConfigurationPool()->getContainer()->getParameter('menu_settings');

        $menus = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.entity_manager')
                ->getRepository('MenuBundle:Menu')
                ->findAll();

        $menusChoice['-'] = 'Hide From Menu';
        $menusChoice['0'] = 'Menu Root';

        foreach ($menus as $menu) {
            $menusChoice[$menu->getId()] = $menu->getTitle() . ' (' . $menu->getMenuGroup() . ')';
        }

        $formMapper
                ->tab('Menu Item Essential Details')
                ->with('Menu Item Essential Details', array('collapsed' => true))
                ->add('title', null, array('label' => 'Title', 'required' => true))
                ->add('menuType', 'choice', array('choices' => Menu::getMenuTypeList(), 'preferred_choices' => array(Menu::TYPE_PAGE), 'label' => 'Menu Item Type', 'required' => true))
                ->add('route', 'choice', array('choices' => Menu::getRouteList(), 'preferred_choices' => array(Menu::ROUTE_SHOWPAGE), 'label' => 'Link Action for Controller', 'required' => true))
                ->setHelps(array(
                    'title' => 'Set the title of the menu item (link copy text)',
                    'menuType' => 'Set the type of the menu item linked page',
                    'route' => 'Select the action of the menu item'
                ))
                ->end()
                ->end()
                ->tab('Menu Item Taxonomy')
                ->with('Menu Item Taxonomy', array('collapsed' => true))
                ->add('menuGroup', 'choice', array('choices' => Menu::getMenuGroupList(), 'preferred_choices' => array(Menu::GROUP_MAIN), 'label' => 'Menu Group', 'required' => true))
                ->add('parent', 'choice', array('choices' => $menusChoice, 'attr' => array('class' => 'autoCompleteItems autoCompleteMenus', 'data-sonata-select2' => 'false'), 'label' => 'Parent Menu Item', 'required' => false))
                ->add('ordering', null, array('label' => 'Menu Item Order', 'required' => true))
                ->setHelps(array(
                    'menuGroup' => 'Set the menu group this menu item belongs to',
                    'parent' => 'Select the parent menu item',
                    'ordering' => 'Set the order of the menu item in accordance to the other menu items of the same menu level'
                ))
                ->end()
                ->end()
                ->tab('Menu Item Access Control')
                ->with('Menu Item Access Control', array('collapsed' => true))
                ->add('accessLevel', 'choice', array('choices' => Menu::getAccessLevelList(), 'preferred_choices' => array(Menu::STATUS_ADMINONLY), 'label' => 'Access Level', 'required' => true))
                ->add('publishState', 'choice', array('choices' => Menu::getPublishStateList(), 'preferred_choices' => array(Menu::STATE_UNPUBLISHED), 'label' => 'Publish State', 'required' => true))
                ->setHelps(array(
                    'accessLevel' => 'Set the minimum access level the item is visible to',
                    'publishState' => 'Set the publish state of this menu item'
                ))
                ->end()
                ->end()
                ->tab('Menu Item Optional Details')
                ->with('Menu Item Optional Details', array('collapsed' => true))
                ->add('menuImage', 'sonata_media_type', array('provider' => 'sonata.media.provider.image', 'context' => 'icons', 'attr' => array('class' => 'imagefield'), 'label' => 'Menu Icon Image', 'required' => false))
                ->setHelps(array(
                    'menuImage' => 'Set an image as Menu Icon'
                ))
                ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {

        // Getting the container parameters set in the config file that exist
        $menuSettings = $this->getConfigurationPool()->getContainer()->getParameter('menu_settings');

        $datagridMapper
                ->add('title')
                ->add('menuGroup', 'doctrine_orm_string', array(), 'choice', array('choices' => Menu::getMenuGroupList()))
                ->add('menuType', 'doctrine_orm_string', array(), 'choice', array('choices' => Menu::getMenuTypeList()))
                ->add('page')
                ->add('blog')
                ->add('publishState', 'doctrine_orm_string', array(), 'choice', array('choices' => Menu::getPublishStateList()))
                ->add('accessLevel', 'doctrine_orm_string', array(), 'choice', array('choices' => Menu::getAccessLevelList()))
        ;
    }

    protected function configureListFields(ListMapper $listMapper) {
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

    public function validate(ErrorElement $errorElement, $object) {
        $errorElement
                ->with('title')
                ->assertLength(array('max' => 255))
                ->end()
        ;
    }

    protected function configureRoutes(RouteCollection $collection) {
        $collection->add('duplicate', $this->getRouterIdParameter() . '/duplicate');
        $collection->add('edit', $this->getRouterIdParameter() . '/edit');
        $collection->add('delete', $this->getRouterIdParameter() . '/delete');
    }

}
