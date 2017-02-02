<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\MenuBundle\Menu;

use BardisCMS\MenuBundle\Entity\Menu as Menu;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{
    private $factory;
    private $em;
    private $container;
    private $menuItemLevel;
    private $allowedAccessLevels;

    /**
     * @param FactoryInterface   $factory
     * @param EntityManager      $em
     * @param ContainerInterface $container
     */
    public function __construct(FactoryInterface $factory, EntityManager $em, ContainerInterface $container)
    {
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
     * @param string  $menuGroup
     * @param string  $cssClass
     * @param string  $menuItemDecorator
     * @param string  $f6Menu
     *
     * @return menu
     */
    public function createMenu(Request $request, $menuGroup, $cssClass, $menuItemDecorator, $f6Menu)
    {
        $repo = $this->em->getRepository('MenuBundle:Menu');
        $menuData = $repo->findBy(array('menuGroup' => $menuGroup), array('ordering' => 'ASC'));

        $menuData = array_values($menuData);
        $menuData = $this->buildTree($menuData, 0);

        $menu = $this->factory->createItem($menuGroup);
        $menu->setChildrenAttribute('class', $cssClass);
        if ($f6Menu !== 'false') {
            $menu->setChildrenAttribute('data-responsive-menu', $f6Menu);
        }

        $matcher = $this->container->get('knp_menu.matcher');
        $voter = $this->container->get('bardiscms_menu.voter.request');
        $matcher->addVoter($voter);

        $this->menuItemLevel = 0;
        $this->setupMenuItem($menu, $menuData, $menuItemDecorator);

        return $menu;
    }

    /**
     * @param array  $elements
     * @param int $parent
     *
     * @return array
     * */
    public function buildTree(array &$elements, $parent = 0)
    {
        $branch = array();

        foreach ($elements as $element) {
            if ((int)$element->getParent() === (int)$parent) {
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
     * @param array    $menuItemList
     * @param string   $menuItemDecorator
     * */
    public function setupMenuItem($menu, $menuItemList, $menuItemDecorator)
    {
        $menuItemCounter = 0;

        foreach ($menuItemList as $menuItem) {
            $menuType = $menuItem->getMenuType();
            $getPageFunction = 'get'.ucfirst($menuType);

            ++$menuItemCounter;

            // Simple publishing ACL based on AccessLevel and user Allowed Access Levels
            $accessAllowedForUserRole = $this->container->get('bardiscms_menu.services.helpers')->isUserAccessAllowedByRole(
                $menuItem->getAccessLevel(),
                $this->allowedAccessLevels
            );

            if ($menuItem->getPublishstate() !== Menu::STATE_UNPUBLISHED && $accessAllowedForUserRole) {
                $urlParams = $menuItem->getMenuUrlExtras();
                if (!empty($urlParams)) {
                    $urlParams = '/'.urlencode($urlParams);
                }

                switch ($menuType) {

                    case Menu::TYPE_EXTERNAL_URL:
                        $targetURL = $menuItem->getExternalUrl();

                        if ($targetURL === null) {
                            $targetURL = '#';
                        }

                        $menu->addChild($menuItem->getTitle(), array(
                            'uri' => $targetURL,
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
                            'uri' => $targetURL,
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
                                $menu->addChild($menuItem->getTitle(), array('uri' => '/'.$menuItem->getRoute().'/'.$pageFunction.$urlParams));
                            } elseif ('index' === $alias) {
                                $menu->addChild($menuItem->getTitle(), array('uri' => '/'));
                            } else {
                                $menu->addChild($menuItem->getTitle(), array('uri' => '/'.$alias.$urlParams));
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
                                $menu->addChild($menuItem->getTitle(), array('uri' => '/'.$menuType.'/'.$menuItem->getRoute().'/'.$menuItem->$getPageFunction().$urlParams));
                            } else {
                                $menu->addChild($menuItem->getTitle(), array('uri' => '/'.$menuType.'/'.$alias.$urlParams));
                            }
                        } else {
                            $menu->addChild($menuItem->getTitle(), array('uri' => '/'));
                        }

                        break;

                    default:
                        $menu->addChild($menuItem->getTitle());
                        $menu[$menuItem->getTitle()]->setLabelAttribute('class', 'divider');
                }

                $menu[$menuItem->getTitle()]->setAttribute('class', 'item'.$menuItemCounter.' level'.$this->menuItemLevel);
                $menu[$menuItem->getTitle()]->setLinkAttribute('class', 'item'.$menuItemCounter.' level'.$this->menuItemLevel);
                $menu[$menuItem->getTitle()]->setLinkAttribute('title', $menuItem->getTitle());

                if ($menuItemDecorator === 'main') {
                    if ($menuItem->children !== null) {
                        $menu[$menuItem->getTitle()]->setAttribute('class', 'item'.$menuItemCounter.' level'.$this->menuItemLevel.' has-submenu');
                        $this->menuItemLevel = $this->menuItemLevel + 1;
                        $menu[$menuItem->getTitle()]->setChildrenAttribute('class', 'menu level'.$this->menuItemLevel);
                        $this->setupMenuItem($menu[$menuItem->getTitle()], $menuItem->children, $menuItemDecorator);
                        $this->menuItemLevel = $this->menuItemLevel - 1;
                    }
                } else {
                    if ($menuItem->children !== null) {
                        $this->menuItemLevel = $this->menuItemLevel + 1;
                        $menu[$menuItem->getTitle()]->setAttribute('class', 'item'.$menuItemCounter.' level'.$this->menuItemLevel);
                        $this->setupMenuItem($menu[$menuItem->getTitle()], $menuItem->children, $menuItemDecorator);
                        $this->menuItemLevel = $this->menuItemLevel - 1;
                    }
                }

                if ($menuItem->getMenuImage() !== null) {
                    //$menu[$menuItem->getTitle()]->setLabelAttribute('style', 'background-image:url("' . $menuItem->getMenuImage() . '");');
                    $menu[$menuItem->getTitle()]->setAttribute('menuImage', $menuItem->getMenuImage());
                }
            }
        }
    }

    /**
     * @param int    $pageId
     * @param string $menuType
     */
    public function getPageAlias($pageId, $menuType)
    {
        $repo = $this->em->getRepository(ucfirst($menuType).'Bundle:'.ucfirst($menuType));
        $page = $repo->findOneById($pageId);

        $pageAlias = $page->getAlias();

        return $pageAlias;
    }
}
