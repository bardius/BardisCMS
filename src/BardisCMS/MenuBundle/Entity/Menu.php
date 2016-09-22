<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\MenuBundle\Entity;

use Application\Sonata\MediaBundle\Entity\Media;
use BardisCMS\BlogBundle\Entity\Blog;
use BardisCMS\PageBundle\Entity\Page;
use Doctrine\ORM\Mapping as ORM;

/**
 * BardisCMS\MenuBundle\Entity\Menu.
 *
 * @ORM\Table(name="menu_items")
 * @ORM\Entity
 */
class Menu
{
    /*
     * Menu Item types
     */
    const TYPE_PAGE = 'page';
    const TYPE_BLOG = 'blog';
    const TYPE_EXTERNAL_URL = 'http';
    const TYPE_INTERNAL_URL = 'url';
    const TYPE_SEPARATOR = 'sep';

    /*
     * AccessLevel states
     */
    const STATUS_HIDDEN = 0;
    const STATUS_PUBLIC = 1;
    const STATUS_ADMINONLY = 2;
    const STATUS_AUTHONLY = 3;
    const STATUS_NONAUTHONLY = 4;

    /*
     * PublishState states
     */
    const STATE_UNPUBLISHED = 0;
    const STATE_PUBLISHED = 1;

    /*
     * Controller Action routes
     */
    const ROUTE_NONE = 'none';
    const ROUTE_SHOWPAGE = 'showPage';

    /*
     * Menu Groups
     */
    const GROUP_MAIN = 'Main Menu';
    const GROUP_FOOTER = 'Footer Menu';
    const GROUP_SMALLFOOTER = 'Small Footer Menu';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $menuType;

    /**
     * @ORM\ManyToOne(targetEntity="BardisCMS\PageBundle\Entity\Page")
     * @ORM\JoinColumn(name="page", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $page;

    /**
     * @ORM\ManyToOne(targetEntity="BardisCMS\BlogBundle\Entity\Blog")
     * @ORM\JoinColumn(name="blog", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $blog;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $route = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $externalUrl = null;

    /**
     * @ORM\Column(type="integer")
     */
    protected $accessLevel;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $parent = 0;

    /**
     * @ORM\Column(type="string")
     */
    protected $menuGroup;

    /**
     * @ORM\Column(type="integer")
     */
    protected $publishState;

    /**
     * @ORM\Column(type="integer")
     */
    protected $ordering = 99;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="menuImage", onDelete="SET NULL")
     */
    protected $menuImage = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $menuUrlExtras;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Menu
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set menuType.
     *
     * @param string $menuType
     *
     * @return Menu
     */
    public function setMenuType($menuType)
    {
        $this->menuType = $menuType;

        return $this;
    }

    /**
     * Get menuType.
     *
     * @return string
     */
    public function getMenuType()
    {
        return $this->menuType;
    }

    /**
     * Set route.
     *
     * @param string $route
     *
     * @return Menu
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route.
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set externalUrl.
     *
     * @param string $externalUrl
     *
     * @return Menu
     */
    public function setExternalUrl($externalUrl)
    {
        $this->externalUrl = $externalUrl;

        return $this;
    }

    /**
     * Get externalUrl.
     *
     * @return string
     */
    public function getExternalUrl()
    {
        return $this->externalUrl;
    }

    /**
     * Set accessLevel.
     *
     * @param int $accessLevel
     *
     * @return Menu
     */
    public function setAccessLevel($accessLevel)
    {
        $this->accessLevel = $accessLevel;

        return $this;
    }

    /**
     * Get accessLevel.
     *
     * @return int
     */
    public function getAccessLevel()
    {
        return $this->accessLevel;
    }

    /**
     * Set parent.
     *
     * @param string $parent
     *
     * @return Menu
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set menuGroup.
     *
     * @param string $menuGroup
     *
     * @return Menu
     */
    public function setMenuGroup($menuGroup)
    {
        $this->menuGroup = $menuGroup;

        return $this;
    }

    /**
     * Get menuGroup.
     *
     * @return string
     */
    public function getMenuGroup()
    {
        return $this->menuGroup;
    }

    /**
     * Set publishState.
     *
     * @param int $publishState
     *
     * @return Menu
     */
    public function setPublishState($publishState)
    {
        $this->publishState = $publishState;

        return $this;
    }

    /**
     * Get publishState.
     *
     * @return int
     */
    public function getPublishState()
    {
        return $this->publishState;
    }

    /**
     * Set ordering.
     *
     * @param int $ordering
     *
     * @return Menu
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering.
     *
     * @return int
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set page.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $page
     *
     * @return Menu
     */
    public function setPage(Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page.
     *
     * @return \BardisCMS\PageBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set blog.
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blog
     *
     * @return Menu
     */
    public function setBlog(Blog $blog = null)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog.
     *
     * @return \BardisCMS\BlogBundle\Entity\Blog
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * Set menuImage.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $menuImage
     *
     * @return Menu
     */
    public function setMenuImage(Media $menuImage = null)
    {
        $this->menuImage = $menuImage;

        return $this;
    }

    /**
     * Get menuImage.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMenuImage()
    {
        return $this->menuImage;
    }

    /**
     * Set menuUrlExtras.
     *
     * @param string $menuUrlExtras
     *
     * @return Menu
     */
    public function setMenuUrlExtras($menuUrlExtras)
    {
        $this->menuUrlExtras = $menuUrlExtras;

        return $this;
    }

    /**
     * Get menuUrlExtras.
     *
     * @return string
     */
    public function getMenuUrlExtras()
    {
        return $this->menuUrlExtras;
    }

    /**
     * toString.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->getTitle()) {
            return (string) $this->getTitle();
        }

        return (string) 'New Menu Item';
    }

    /**
     * Returns AccessLevel list.
     *
     * @return array
     */
    public static function getAccessLevelList()
    {
        return array(
            self::STATUS_HIDDEN => 'Hidden',
            self::STATUS_PUBLIC => 'Public',
            self::STATUS_ADMINONLY => 'Administrator Only',
            self::STATUS_AUTHONLY => 'Authenticated Users Only',
            self::STATUS_NONAUTHONLY => 'Anonymous Users Only',
        );
    }

    /**
     * toString AccessLevel.
     *
     * @return string
     */
    public function getAccessLevelAsString()
    {
        switch ($this->getAccessLevel()) {
            case self::STATUS_HIDDEN:      return 'Hidden';
            case self::STATUS_PUBLIC:      return 'Public';
            case self::STATUS_ADMINONLY:   return 'Administrator Only';
            case self::STATUS_AUTHONLY:    return 'Authenticated Users Only';
            case self::STATUS_NONAUTHONLY: return 'Anonymous Users Only';
            default:                        return $this->getAccessLevel();
        }
    }

    /**
     * Returns MenuType list.
     *
     * @return array
     */
    public static function getMenuTypeList()
    {
        return array(
            self::TYPE_PAGE => 'Page',
            self::TYPE_BLOG => 'Blog',
            self::TYPE_EXTERNAL_URL => 'External URL',
            self::TYPE_INTERNAL_URL => 'Internal URL',
            self::TYPE_SEPARATOR => 'Separator',
        );
    }

    /**
     * toString MenuType.
     *
     * @return string
     */
    public function getMenuTypeAsString()
    {
        switch ($this->getMenuType()) {
            case self::TYPE_PAGE:          return 'Page';
            case self::TYPE_BLOG:          return 'Blog';
            case self::TYPE_EXTERNAL_URL:  return 'External URL';
            case self::TYPE_INTERNAL_URL:  return 'Internal URL';
            case self::TYPE_SEPARATOR:     return 'Separator';
            default:                        return $this->getMenuType();
        }
    }

    /**
     * Returns PublishState list.
     *
     * @return array
     */
    public static function getPublishStateList()
    {
        return array(
            self::STATE_UNPUBLISHED => 'Unpublished',
            self::STATE_PUBLISHED => 'Published',
        );
    }

    /**
     * toString PublishState.
     *
     * @return string
     */
    public function getPublishStateAsString()
    {
        switch ($this->getPublishState()) {
            case self::STATE_UNPUBLISHED:  return 'Unpublished';
            case self::STATE_PUBLISHED:    return 'Published';
            default:                        return $this->getPublishState();
        }
    }

    /**
     * Returns Route list.
     *
     * @return array
     */
    public static function getRouteList()
    {
        return array(
            self::ROUTE_NONE => 'None',
            self::ROUTE_SHOWPAGE => 'Show Page Action',
        );
    }

    /**
     * toString Route.
     *
     * @return string
     */
    public function getRouteAsString()
    {
        switch ($this->getRoute()) {
            case self::ROUTE_NONE:     return 'None';
            case self::ROUTE_SHOWPAGE: return 'Show Page Action';
            default:                    return $this->getRoute();
        }
    }

    /**
     * Returns MenuGroup list.
     *
     * @return array
     */
    public static function getMenuGroupList()
    {
        return array(
            self::GROUP_MAIN => 'Main Menu',
            self::GROUP_FOOTER => 'Footer Menu',
            self::GROUP_SMALLFOOTER => 'Small Footer Menu',
        );
    }

    /**
     * toString MenuGroup.
     *
     * @return string
     */
    public function getMenuGroupAsString()
    {
        switch ($this->getMenuGroup()) {
            case self::GROUP_MAIN:         return 'Main Menu';
            case self::GROUP_FOOTER:       return 'Footer Menu';
            case self::GROUP_SMALLFOOTER:  return 'Small Footer Menu';
            default:                        return $this->getMenuGroup();
        }
    }
}
