<?php

namespace BardisCMS\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use BardisCMS\PageBundle\Entity\Page;
use BardisCMS\BlogBundle\Entity\Blog;
use Application\Sonata\MediaBundle\Entity\Media;

/**
 * BardisCMS\MenuBundle\Entity\Menu
 *
 * @ORM\Table(name="menu_items")
 * @ORM\Entity
 */
class Menu {

    /*
     * Menu Item types
     */
    const TYPE_PAGE             = "page";
    const TYPE_BLOG             = "blog";
    const TYPE_EXTERNAL_URL     = "http";
    const TYPE_INTERNAL_URL     = "url";
    const TYPE_SEPARATOR        = "sep";

    /*
     * AccessLevel states
     */
    const STATUS_HIDDEN         = 0;
    const STATUS_PUBLIC         = 1;
    const STATUS_ADMINONLY      = 2;
    const STATUS_AUTHONLY       = 3;
    const STATUS_NONAUTHONLY    = 4;

    /*
     * PublishState states
     */
    const STATE_UNPUBLISHED = 0;
    const STATE_PUBLISHED   = 1;

    /*
     * Controller Action routes
     */
    const ROUTE_NONE       = "none";
    const ROUTE_SHOWPAGE   = "showPage";

    /*
     * Menu Groups
     */
    const GROUP_MAIN        = "Main Menu";
    const GROUP_FOOTER      = "Footer Menu";
    const GROUP_SMALLFOOTER = "Small Footer Menu";

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
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Menu
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set menuType
     *
     * @param string $menuType
     * @return Menu
     */
    public function setMenuType($menuType) {
        $this->menuType = $menuType;
        return $this;
    }

    /**
     * Get menuType
     *
     * @return string
     */
    public function getMenuType() {
        return $this->menuType;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return Menu
     */
    public function setRoute($route) {
        $this->route = $route;
        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute() {
        return $this->route;
    }

    /**
     * Set externalUrl
     *
     * @param string $externalUrl
     * @return Menu
     */
    public function setExternalUrl($externalUrl) {
        $this->externalUrl = $externalUrl;
        return $this;
    }

    /**
     * Get externalUrl
     *
     * @return string
     */
    public function getExternalUrl() {
        return $this->externalUrl;
    }

    /**
     * Set accessLevel
     *
     * @param integer $accessLevel
     * @return Menu
     */
    public function setAccessLevel($accessLevel) {
        $this->accessLevel = $accessLevel;
        return $this;
    }

    /**
     * Get accessLevel
     *
     * @return integer
     */
    public function getAccessLevel() {
        return $this->accessLevel;
    }

    /**
     * Set parent
     *
     * @param string $parent
     * @return Menu
     */
    public function setParent($parent) {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return string
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Set menuGroup
     *
     * @param string $menuGroup
     * @return Menu
     */
    public function setMenuGroup($menuGroup) {
        $this->menuGroup = $menuGroup;
        return $this;
    }

    /**
     * Get menuGroup
     *
     * @return string
     */
    public function getMenuGroup() {
        return $this->menuGroup;
    }

    /**
     * Set publishState
     *
     * @param integer $publishState
     * @return Menu
     */
    public function setPublishState($publishState) {
        $this->publishState = $publishState;
        return $this;
    }

    /**
     * Get publishState
     *
     * @return integer
     */
    public function getPublishState() {
        return $this->publishState;
    }

    /**
     * Set ordering
     *
     * @param integer $ordering
     * @return Menu
     */
    public function setOrdering($ordering) {
        $this->ordering = $ordering;
        return $this;
    }

    /**
     * Get ordering
     *
     * @return integer
     */
    public function getOrdering() {
        return $this->ordering;
    }

    /**
     * Set page
     *
     * @param \BardisCMS\PageBundle\Entity\Page $page
     * @return Menu
     */
    public function setPage(Page $page = null) {
        $this->page = $page;
        return $this;
    }

    /**
     * Get page
     *
     * @return \BardisCMS\PageBundle\Entity\Page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Set blog
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blog
     * @return Menu
     */
    public function setBlog(Blog $blog = null) {
        $this->blog = $blog;
        return $this;
    }

    /**
     * Get blog
     *
     * @return \BardisCMS\BlogBundle\Entity\Blog
     */
    public function getBlog() {
        return $this->blog;
    }

    /**
     * Set menuImage
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $menuImage
     * @return Menu
     */
    public function setMenuImage(Media $menuImage = null) {
        $this->menuImage = $menuImage;
        return $this;
    }

    /**
     * Get menuImage
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getMenuImage() {
        return $this->menuImage;
    }

    /**
     * Set menuUrlExtras
     *
     * @param string $menuUrlExtras
     * @return Menu
     */
    public function setMenuUrlExtras($menuUrlExtras) {
        $this->menuUrlExtras = $menuUrlExtras;
        return $this;
    }

    /**
     * Get menuUrlExtras
     *
     * @return string
     */
    public function getMenuUrlExtras() {
        return $this->menuUrlExtras;
    }

    /**
     * toString
     *
     * @return string
     */
    public function __toString() {
        if ($this->getTitle()) {
            return (string) $this->getTitle();
        } else {
            return (string) 'New Menu Item';
        }
    }

    /**
     * Returns AccessLevel list.
     *
     * @return array
     */
    public static function getAccessLevelList()
    {
        return array(
            Menu::STATUS_HIDDEN      => "Hidden",
            Menu::STATUS_PUBLIC      => "Public",
            Menu::STATUS_ADMINONLY   => "Administrator Only",
            Menu::STATUS_AUTHONLY    => "Authenticated Users Only",
            Menu::STATUS_NONAUTHONLY => "Anonymous Users Only"
        );
    }

    /**
     * toString AccessLevel
     *
     * @return string
     */
    public function getAccessLevelAsString() {
        switch ($this->getAccessLevel()) {
            case(Menu::STATUS_HIDDEN):      return "Hidden";
            case(Menu::STATUS_PUBLIC):      return "Public";
            case(Menu::STATUS_ADMINONLY):   return "Administrator Only";
            case(Menu::STATUS_AUTHONLY):    return "Authenticated Users Only";
            case(Menu::STATUS_NONAUTHONLY): return "Anonymous Users Only";
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
            Menu::TYPE_PAGE             => "Page",
            Menu::TYPE_BLOG             => "Blog",
            Menu::TYPE_EXTERNAL_URL     => "External URL",
            Menu::TYPE_INTERNAL_URL     => "Internal URL",
            Menu::TYPE_SEPARATOR        => "Separator"
        );
    }

    /**
     * toString MenuType
     *
     * @return string
     */
    public function getMenuTypeAsString() {
        switch ($this->getMenuType()) {
            case(Menu::TYPE_PAGE):          return "Page";
            case(Menu::TYPE_BLOG):          return "Blog";
            case(Menu::TYPE_EXTERNAL_URL):  return "External URL";
            case(Menu::TYPE_INTERNAL_URL):  return "Internal URL";
            case(Menu::TYPE_SEPARATOR):     return "Separator";
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
            Menu::STATE_UNPUBLISHED => "Unpublished",
            Menu::STATE_PUBLISHED   => "Published"
        );
    }

    /**
     * toString PublishState
     *
     * @return string
     */
    public function getPublishStateAsString() {
        switch ($this->getPublishState()) {
            case(Menu::STATE_UNPUBLISHED):  return "Unpublished";
            case(Menu::STATE_PUBLISHED):    return "Published";
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
            Menu::ROUTE_NONE        => "None",
            Menu::ROUTE_SHOWPAGE    => "Show Page Action"
        );
    }

    /**
     * toString Route
     *
     * @return string
     */
    public function getRouteAsString() {
        switch ($this->getRoute()) {
            case(Menu::ROUTE_NONE):     return "None";
            case(Menu::ROUTE_SHOWPAGE): return "Show Page Action";
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
            Menu::GROUP_MAIN        => "Main Menu",
            Menu::GROUP_FOOTER      => "Footer Menu",
            Menu::GROUP_SMALLFOOTER => "Small Footer Menu"
        );
    }

    /**
     * toString MenuGroup
     *
     * @return string
     */
    public function getMenuGroupAsString() {
        switch ($this->getMenuGroup()) {
            case(Menu::GROUP_MAIN):         return "Main Menu";
            case(Menu::GROUP_FOOTER):       return "Footer Menu";
            case(Menu::GROUP_SMALLFOOTER):  return "Small Footer Menu";
            default:                        return $this->getMenuGroup();
        }
    }

}
