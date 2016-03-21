<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use BardisCMS\ContentBlockBundle\Entity\ContentBlock;
use BardisCMS\CategoryBundle\Entity\Category;
use BardisCMS\TagBundle\Entity\Tag;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * BardisCMS\PageBundle\Entity\Page
 *
 * @ORM\Table(name="pages")
 * @DoctrineAssert\UniqueEntity(fields="alias", message="Alias must be unique")
 * @ORM\Entity(repositoryClass="BardisCMS\PageBundle\Repository\PageRepository")
 */
class Page {

    /*
     * Publish states
     */
    const STATUS_UNPUBLISHED    = 0;
    const STATUS_PUBLISHED      = 1;
    const STATUS_PREVIEW        = 2;
    const STATUS_NONAUTHONLY    = 3;
    const STATUS_AUTHONLY       = 4;

    /*
     * Error Page statuses
     */
    const ERROR_404 = "404";
    const ERROR_403 = "403";
    const ERROR_401 = "401";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="date")
     */
    protected $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="author", onDelete="SET NULL")
     */
    protected $author;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique = true)
     */
    protected $alias = null;

    /**
     * @ORM\Column(type="integer")
     */
    protected $pageOrder = 99;

    /**
     * @ORM\Column(type="integer")
     */
    protected $showPageTitle;

    /**
     * @ORM\Column(type="integer")
     */
    protected $publishState;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $pageclass = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $keywords = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $introtext = null;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="introimage", onDelete="SET NULL")
     */
    protected $introimage;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     * @ORM\JoinColumn(name="bgimage", onDelete="SET NULL")
     */
    protected $bgimage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $intromediasize = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $introclass = null;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\CategoryBundle\Entity\Category", inversedBy="pages", cascade={"persist"})
     * @ORM\JoinTable(name="pages_categories")
     */
    protected $categories;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\TagBundle\Entity\Tag", inversedBy="pages", cascade={"persist"})
     * @ORM\JoinTable(name="pages_tags")
     */
    protected $tags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $pagetype = null;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\ContentBlockBundle\Entity\ContentBlock", inversedBy="maincontents", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="maincontent_blocks")
     * */
    protected $maincontentblocks;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\ContentBlockBundle\Entity\ContentBlock", inversedBy="bannercontents", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="bannercontent_blocks")
     * */
    protected $bannercontentblocks;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\ContentBlockBundle\Entity\ContentBlock", inversedBy="secondarycontents", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="secondarycontent_blocks")
     * */
    protected $secondarycontentblocks;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\ContentBlockBundle\Entity\ContentBlock", inversedBy="extracontents", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="extracontent_blocks")
     * */
    protected $extracontentblocks;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\ContentBlockBundle\Entity\ContentBlock", inversedBy="modalcontents", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="modalcontent_blocks")
     * */
    protected $modalcontentblocks;

    /**
     * @ORM\Column(name="date_last_modified", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $dateLastModified;

    public function __construct() {
        $this->modalcontentblocks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->extracontentblocks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->secondarycontentblocks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->maincontentblocks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bannercontentblocks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return Page
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return date
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Page
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
     * Set author
     *
     * @param string $author
     * @return Page
     */
    public function setAuthor($author) {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor() {
        return $this->author;
    }

    /**
     * Set pageclass
     *
     * @param string $pageclass
     * @return Page
     */
    public function setPageclass($pageclass) {
        $this->pageclass = $pageclass;

        return $this;
    }

    /**
     * Get pageclass
     *
     * @return string
     */
    public function getPageclass() {
        return $this->pageclass;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Page
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     * @return Page
     */
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords() {
        return $this->keywords;
    }

    /**
     * Set introtext
     *
     * @param text $introtext
     * @return Page
     */
    public function setIntrotext($introtext) {
        $this->introtext = $introtext;
        return $this;
    }

    /**
     * Get introtext
     *
     * @return text
     */
    public function getIntrotext() {
        return $this->introtext;
    }

    /**
     * Set introimage
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $introimage
     * @return Page
     */
    public function setIntroimage(\Application\Sonata\MediaBundle\Entity\Media $introimage = null) {
        $this->introimage = $introimage;
        return $this;
    }

    /**
     * Get introimage
     *
     * @return Application\Sonata\MediaBundle\Entity\Media
     */
    public function getIntroimage() {
        return $this->introimage;
    }

    /**
     * Add introimage
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $introimage
     * @return Page
     */
    public function addIntroimage(\Application\Sonata\MediaBundle\Entity\Media $introimage) {
        $this->introimage[] = $introimage;
        return $this;
    }

    /**
     * Remove introimage
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $introimage
     */
    public function removeIntroimage(\Application\Sonata\MediaBundle\Entity\Media $introimage) {
        $this->introimage->removeElement($introimage);
    }

    /**
     * Set bgimage
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $bgimage
     * @return Page
     */
    public function setBgimage(\Application\Sonata\MediaBundle\Entity\Media $bgimage = null) {
        $this->bgimage = $bgimage;

        return $this;
    }

    /**
     * Get bgimage
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getBgimage() {
        return $this->bgimage;
    }

    /**
     * Set introclass
     *
     * @param string $introclass
     * @return Page
     */
    public function setIntroclass($introclass) {
        $this->introclass = $introclass;

        return $this;
    }

    /**
     * Get introclass
     *
     * @return string
     */
    public function getIntroclass() {
        return $this->introclass;
    }

    /**
     * Set pagetype
     *
     * @param string $pagetype
     * @return Page
     */
    public function setPagetype($pagetype) {
        $this->pagetype = $pagetype;

        return $this;
    }

    /**
     * Get pagetype
     *
     * @return string
     */
    public function getPagetype() {
        return $this->pagetype;
    }

    /**
     * Add maincontentblocks
     *
     * @param BardisCMS\ContentBlockBundle\Entity\ContentBlock $maincontentblocks
     * @return Page
     */
    public function addMaincontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $maincontentblocks) {
        $this->maincontentblocks[] = $maincontentblocks;

        return $this;
    }

    /**
     * Remove maincontentblocks
     *
     * @param BardisCMS\ContentBlockBundle\Entity\ContentBlock $maincontentblocks
     */
    public function removeMaincontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $maincontentblocks) {
        $this->maincontentblocks->removeElement($maincontentblocks);
    }

    /**
     * Get maincontentblocks
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getMaincontentblocks() {
        return $this->maincontentblocks;
    }

    /**
     * Add secondarycontentblocks
     *
     * @param BardisCMS\ContentBlockBundle\Entity\ContentBlock $secondarycontentblocks
     * @return Page
     */
    public function addSecondarycontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $secondarycontentblocks) {
        $this->secondarycontentblocks[] = $secondarycontentblocks;

        return $this;
    }

    /**
     * Remove secondarycontentblocks
     *
     * @param BardisCMS\ContentBlockBundle\Entity\ContentBlock $secondarycontentblocks
     */
    public function removeSecondarycontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $secondarycontentblocks) {
        $this->secondarycontentblocks->removeElement($secondarycontentblocks);
    }

    /**
     * Get secondarycontentblocks
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSecondarycontentblocks() {
        return $this->secondarycontentblocks;
    }

    /**
     * Add extracontentblocks
     *
     * @param BardisCMS\ContentBlockBundle\Entity\ContentBlock $extracontentblocks
     * @return Page
     */
    public function addExtracontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $extracontentblocks) {
        $this->extracontentblocks[] = $extracontentblocks;

        return $this;
    }

    /**
     * Remove extracontentblocks
     *
     * @param BardisCMS\ContentBlockBundle\Entity\ContentBlock $extracontentblocks
     */
    public function removeExtracontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $extracontentblocks) {
        $this->extracontentblocks->removeElement($extracontentblocks);
    }

    /**
     * Get extracontentblocks
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getExtracontentblocks() {
        return $this->extracontentblocks;
    }

    /**
     * Add modalcontentblocks
     *
     * @param BardisCMS\ContentBlockBundle\Entity\ContentBlock $modalcontentblocks
     * @return Page
     */
    public function addModalcontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $modalcontentblocks) {
        $this->modalcontentblocks[] = $modalcontentblocks;

        return $this;
    }

    /**
     * Remove modalcontentblocks
     *
     * @param BardisCMS\ContentBlockBundle\Entity\ContentBlock $modalcontentblocks
     */
    public function removeModalcontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $modalcontentblocks) {
        $this->modalcontentblocks->removeElement($modalcontentblocks);
    }

    /**
     * Get modalcontentblocks
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getModalcontentblocks() {
        return $this->modalcontentblocks;
    }

    /**
     * Add categories
     *
     * @param BardisCMS\CategoryBundle\Entity\Category $categories
     * @return Page
     */
    public function addCategory(\BardisCMS\CategoryBundle\Entity\Category $categories) {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param BardisCMS\CategoryBundle\Entity\Category $categories
     */
    public function removeCategory(\BardisCMS\CategoryBundle\Entity\Category $categories) {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCategories() {
        return $this->categories;
    }

    /**
     * Add tags
     *
     * @param BardisCMS\TagBundle\Entity\Tag $tags
     * @return Page
     */
    public function addTag(\BardisCMS\TagBundle\Entity\Tag $tags) {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param BardisCMS\TagBundle\Entity\Tag $tags
     */
    public function removeTag(\BardisCMS\TagBundle\Entity\Tag $tags) {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * Add categories
     *
     * @param BardisCMS\CategoryBundle\Entity\Category $categories
     * @return Page
     */
    public function addCategories(\BardisCMS\CategoryBundle\Entity\Category $categories) {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param BardisCMS\CategoryBundle\Entity\Category $categories
     */
    public function removeCategories(\BardisCMS\CategoryBundle\Entity\Category $categories) {
        $this->categories->removeElement($categories);
    }

    /**
     * Set intromediasize
     *
     * @param string $intromediasize
     * @return Page
     */
    public function setIntromediasize($intromediasize) {
        $this->intromediasize = $intromediasize;

        return $this;
    }

    /**
     * Get intromediasize
     *
     * @return string
     */
    public function getIntromediasize() {
        return $this->intromediasize;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Page
     */
    public function setAlias($alias) {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * Set showPageTitle
     *
     * @param integer $showPageTitle
     * @return Page
     */
    public function setShowPageTitle($showPageTitle) {
        $this->showPageTitle = $showPageTitle;

        return $this;
    }

    /**
     * Get showPageTitle
     *
     * @return integer
     */
    public function getShowPageTitle() {
        return $this->showPageTitle;
    }

    /**
     * Add bannercontentblocks
     *
     * @param BardisCMS\ContentBlockBundle\Entity\ContentBlock $bannercontentblocks
     * @return Page
     */
    public function addBannercontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $bannercontentblocks) {
        $this->bannercontentblocks[] = $bannercontentblocks;

        return $this;
    }

    /**
     * Remove bannercontentblocks
     *
     * @param BardisCMS\ContentBlockBundle\Entity\ContentBlock $bannercontentblocks
     */
    public function removeBannercontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $bannercontentblocks) {
        $this->bannercontentblocks->removeElement($bannercontentblocks);
    }

    /**
     * Get bannercontentblocks
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getBannercontentblocks() {
        return $this->bannercontentblocks;
    }

    /**
     * Set pageOrder
     *
     * @param integer $pageOrder
     * @return Page
     */
    public function setPageOrder($pageOrder) {
        $this->pageOrder = $pageOrder;

        return $this;
    }

    /**
     * Get pageOrder
     *
     * @return integer
     */
    public function getPageOrder() {
        return $this->pageOrder;
    }

    /**
     * Set publishState
     *
     * @param integer $publishState
     * @return Page
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
     * Get dateLastModified
     *
     * @return integer
     */
    public function getDateLastModified() {
        return $this->dateLastModified;
    }

    /**
     * Set dateLastModified
     *
     * @param integer $dateLastModified
     * @return Page
     */
    public function setDateLastModified($dateLastModified) {
        $this->dateLastModified = $dateLastModified;

        return $this;
    }

    /**
     * toString Title
     *
     * @return string
     */
    public function __toString() {
        if ($this->getTitle()) {
            return (string) $this->getTitle();
        } else {
            return (string) 'New Page';
        }
    }

    /**
     * Returns ErrorStatus list.
     *
     * @return array
     */
    public static function getErrorStatusList()
    {
        return array(
            Page::ERROR_404 => "404",
            Page::ERROR_403 => "403",
            Page::ERROR_401 => "401",
        );
    }

    /**
     * Returns PublishState list.
     *
     * @return array
     */
    public static function getPublishStateList()
    {
        return array(
            Page::STATUS_UNPUBLISHED    => "Unpublished",
            Page::STATUS_PUBLISHED      => "Published",
            Page::STATUS_PREVIEW        => "Preview",
            Page::STATUS_NONAUTHONLY    => "Anonymous Users Only",
            Page::STATUS_AUTHONLY       => "Authenticated Users Only",
        );
    }

    /**
     * toString PublishState
     *
     * @return string
     */
    public function getPublishStateAsString() {
        // Defining the string values of the publish states
        switch ($this->getPublishState()) {
            case(Page::STATUS_UNPUBLISHED): return "Unpublished";
            case(Page::STATUS_PUBLISHED): return "Published";
            case(Page::STATUS_PREVIEW): return "Preview";
            case(Page::STATUS_NONAUTHONLY): return "Anonymous Users Only";
            case(Page::STATUS_AUTHONLY): return "Authenticated Users Only";
            default: return $this->getPublishState();
        }
    }

    /**
     * toString Pagetype
     *
     * @return string
     */
    public function getPagetypeAsString() {
        // Defining the string values of the page types
        switch ($this->getPagetype()) {
            case('one_columned'): return "One Column Page";
            case('two_columned'): return "Two Column Page";
            case('three_columned'): return "Three Column Page";
            case('category_page'): return "Category Page";
            case('page_tag_list'): return "Tagged Page List";
            case('user_profile'): return "User Profile Page";
            case('system_page'): return "System Page";
            case('homepage'): return "Homepage";
            default: return $this->getPagetype();
        }
    }
}
