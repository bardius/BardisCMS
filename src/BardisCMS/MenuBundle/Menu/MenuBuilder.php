<?php

/*
 * Menu Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\MenuBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

use BardisCMS\MenuBundle\Entity\Menu as Menu;

class MenuBuilder {

    private $factory;
    private $em;
    private $container;
    private $menuItemLevel;
    private $allowedAccessLevels;

    /**
     * @param FactoryInterface $factory
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct(FactoryInterface $factory, EntityManager $em, ContainerInterface $container) {
        $this->factory = $factory;
        $this->em = $em;
        $this->container = $container;

        // Get the highest user role security permission
        $this->userRole = $this->container->get('sonata_user.services.helpers')->getLoggedUserHighestRole();

        // Set the AccessLevels that are available for the user
        $this->allowedAccessLevels = $this->container->get('bardiscms_menu.services.helpers')->getAllowedAccessLevels($this->userRole);
    }

    /**
     * @param Request $request
     * @param String $menuGroup
     * @param String $cssClass
     * @param String $menuItemDecorator
     *
     * @return menu
     */
    public function createMenu(Request $request, $menuGroup, $cssClass, $menuItemDecorator) {

        $repo = $this->em->getRepository('MenuBundle:Menu');
        $menuData = $repo->findBy(array("menuGroup" => $menuGroup), array("ordering" => "ASC"));

        $menuData = array_values($menuData);
        $menuData = $this->buildTree($menuData);

        $menu = $this->factory->createItem($menuGroup);
        $menu->setChildrenAttribute('class', $cssClass);

        $matcher = $this->container->get('knp_menu.matcher');
        $voter = $this->container->get('bardiscms_menu.voter.request');
        $matcher->addVoter($voter);

        $this->menuItemLevel = 0;
        $this->setupMenuItem($menu, $menuData, $menuItemDecorator);

        return $menu;
    }

    /**
     * @param Array $elements
     * @param String $parent
     *
     * @return array
     * */
    public function buildTree(array &$elements, $parent = '0') {
        $branch = array();

        foreach ($elements as $element) {
            if ($element->getParent() == $parent) {
                $children = $this->buildTree($elements, $element->getId());
                if ($children) {
                    $element->children = $children;
                } else {
                    $element->children = null;
                }
                $branch[$element->getId()] = $element;
            }
        }

        return $branch;
    }

    /**
     * @param MenuItem $menu
     * @param Array $menuItemList
     * @param String $menuItemDecorator
     * */
    public function setupMenuItem($menu, $menuItemList, $menuItemDecorator) {
        $menuItemCounter = 0;

        foreach ($menuItemList as $menuItem) {
            $menuType = $menuItem->getMenuType();
            $getPageFunction = 'get' . ucfirst($menuType);

            $menuItemCounter++;

            // Simple publishing ACL based on AccessLevel and user Allowed Access Levels
            $accessAllowedForUserRole = $this->container->get('bardiscms_menu.services.helpers')->isUserAccessAllowedByRole(
                $menuItem->getAccessLevel(),
                $this->allowedAccessLevels
            );

            if ($menuItem->getPublishstate() != '0' && $accessAllowedForUserRole) {
                $urlParams = $menuItem->getMenuUrlExtras();
                if (!empty($urlParams)) {
                    $urlParams = '/' . urlencode($urlParams);
                }

                switch ($menuType) {

                    case Menu::TYPE_EXTERNAL_URL:
                        $targetURL = $menuItem->getExternalUrl();

                        if ($targetURL === null) {
                            $targetURL = '#';
                        }

                        $menu->addChild($menuItem->getTitle(), array(
                            'uri' => $targetURL
                        ));
                        $menu[$menuItem->getTitle()]->setLinkAttribute('target', '_blank');
                        $menu[$menuItem->getTitle()]->setLinkAttribute('rel', 'nofollow');

                        break;

                    case Menu::TYPE_INTERNAL_URL:
                        $targetURL = $menuItem->getExternalUrl();

                        if ($targetURL === null) {
                            $targetURL = '#';
                        }

                        $menu->addChild($menuItem->getTitle(), array(
                            'uri' => $targetURL
                        ));

                        break;

                    case Menu::TYPE_SEPARATOR:
                        $menu->addChild($menuItem->getTitle());
                        $menu[$menuItem->getTitle()]->setLabelAttribute('class', 'divider');

                        break;

                    case Menu::TYPE_PAGE:
                        $pageFunction = $menuItem->$getPageFunction();

                        // If Link Action is not selected point to homepage else to alias or page id based route
                        if ($pageFunction !== null) {
                            $alias = $this->getPageAlias($pageFunction, ucfirst($menuType));

                            if (null === $alias) {
                                $menu->addChild($menuItem->getTitle(), array('uri' => '/' . $menuItem->getRoute() . '/' . $pageFunction . $urlParams));
                            } elseif ('index' === $alias) {
                                $menu->addChild($menuItem->getTitle(), array('uri' => '/'));
                            } else {
                                $menu->addChild($menuItem->getTitle(), array('uri' => '/' . $alias . $urlParams));
                            }
                        } else {
                            $menu->addChild($menuItem->getTitle(), array('uri' => '/'));
                        }

                        break;

                    case Menu::TYPE_BLOG:
                        $pageFunction = $menuItem->$getPageFunction();

                        // If Link Action is not selected point to homepage else to alias or page id based route
                        if ($pageFunction !== null) {
                            $alias = $this->getPageAlias($pageFunction, ucfirst($menuType));

                            if (null === $alias) {
                                $menu->addChild($menuItem->getTitle(), array('uri' => '/' . $menuType . '/' . $menuItem->getRoute() . '/' . $menuItem->$getPageFunction() . $urlParams));
                            } else {
                                $menu->addChild($menuItem->getTitle(), array('uri' => '/' . $menuType . '/' . $alias . $urlParams));
                            }
                        } else {
                            $menu->addChild($menuItem->getTitle(), array('uri' => '/'));
                        }

                        break;

                    default:
                        $menu->addChild($menuItem->getTitle());
                        $menu[$menuItem->getTitle()]->setLabelAttribute('class', 'divider');
                }

                $menu[$menuItem->getTitle()]->setAttribute('class', 'item' . $menuItemCounter . ' level' . $this->menuItemLevel);
                $menu[$menuItem->getTitle()]->setLinkAttribute('class', 'item' . $menuItemCounter . ' level' . $this->menuItemLevel);
                $menu[$menuItem->getTitle()]->setLinkAttribute('title', $menuItem->getTitle());

                if ($menuItemDecorator == 'main') {
                    if ($menuItem->getMenuImage() !== null) {
                        $menu[$menuItem->getTitle()]->setLabelAttribute('style', 'background-image:url("' . $menuItem->getMenuImage() . '");');
                    }

                    if ($menuItem->children !== null) {
                        $menu[$menuItem->getTitle()]->setAttribute('class', 'item' . $menuItemCounter . ' level' . $this->menuItemLevel . ' has-dropdown not-click');
                        $this->menuItemLevel = $this->menuItemLevel + 1;
                        //$menu[$menuItem->getTitle()]->setAttribute('flyout-toggle', true);
                        $menu[$menuItem->getTitle()]->setChildrenAttribute('class', 'dropdown level' . $this->menuItemLevel);
                        $this->setupMenuItem($menu[$menuItem->getTitle()], $menuItem->children, $menuItemDecorator);
                        $this->menuItemLevel = $this->menuItemLevel - 1;
                    }
                } else {
                    if ($menuItem->children !== null) {
                        $this->menuItemLevel = $this->menuItemLevel + 1;
                        $menu[$menuItem->getTitle()]->setAttribute('class', 'item' . $menuItemCounter . ' level' . $this->menuItemLevel);
                        $this->setupMenuItem($menu[$menuItem->getTitle()], $menuItem->children, $menuItemDecorator);
                        $this->menuItemLevel = $this->menuItemLevel - 1;
                    }
                }
            }
        }
    }

    /**
     * @param Integer $pageId
     * @param String $menuType
     */
    public function getPageAlias($pageId, $menuType) {
        $repo = $this->em->getRepository(ucfirst($menuType) . 'Bundle:' . ucfirst($menuType));
        $page = $repo->findOneById($pageId);

        $pageAlias = $page->getAlias();

        return $pageAlias;
    }

}
