<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\SkeletonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * BardisCMS\SkeletonBundle\Entity\Skeleton.
 *
 * @ORM\Table(name="skeletons")
 * @DoctrineAssert\UniqueEntity(fields="alias", message="Alias must be unique")
 * @ORM\Entity(repositoryClass="BardisCMS\SkeletonBundle\Repository\SkeletonRepository")
 */
class Skeleton
{
    /*
     * Publish states
     */
    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_PREVIEW = 2;
    const STATUS_NONAUTHONLY = 3;
    const STATUS_AUTHONLY = 4;

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
     * @ORM\JoinColumn(name="introvideo", onDelete="SET NULL")
     */
    protected $introvideo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $intromediasize = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $introclass = null;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\CategoryBundle\Entity\Category", inversedBy="skeletons", cascade={"persist"})
     * @ORM\JoinTable(name="skeletons_categories")
     */
    protected $categories;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\TagBundle\Entity\Tag", inversedBy="skeletons", cascade={"persist"})
     * @ORM\JoinTable(name="skeletons_tags")
     */
    protected $tags;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $pagetype = null;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\ContentBlockBundle\Entity\ContentBlock", inversedBy="skeleton_maincontents", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="skeleton_maincontent_blocks")
     * */
    protected $maincontentblocks;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\ContentBlockBundle\Entity\ContentBlock", inversedBy="skeleton_bannercontents", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="skeleton_bannercontent_blocks")
     * */
    protected $bannercontentblocks;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\ContentBlockBundle\Entity\ContentBlock", inversedBy="skeleton_modalcontents", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="skeleton_modalcontent_blocks")
     * */
    protected $modalcontentblocks;

    /**
     * @ORM\Column(name="date_last_modified", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $dateLastModified;

    public function __construct()
    {
        $this->modalcontentblocks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->maincontentblocks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bannercontentblocks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
    }

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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Skeleton
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Skeleton
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
     * Set alias.
     *
     * @param string $alias
     *
     * @return Skeleton
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set pageOrder.
     *
     * @param int $pageOrder
     *
     * @return Skeleton
     */
    public function setPageOrder($pageOrder)
    {
        $this->pageOrder = $pageOrder;

        return $this;
    }

    /**
     * Get pageOrder.
     *
     * @return int
     */
    public function getPageOrder()
    {
        return $this->pageOrder;
    }

    /**
     * Set showPageTitle.
     *
     * @param int $showPageTitle
     *
     * @return Skeleton
     */
    public function setShowPageTitle($showPageTitle)
    {
        $this->showPageTitle = $showPageTitle;

        return $this;
    }

    /**
     * Get showPageTitle.
     *
     * @return int
     */
    public function getShowPageTitle()
    {
        return $this->showPageTitle;
    }

    /**
     * Set publishState.
     *
     * @param int $publishState
     *
     * @return Skeleton
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
     * Set pageclass.
     *
     * @param string $pageclass
     *
     * @return Skeleton
     */
    public function setPageclass($pageclass)
    {
        $this->pageclass = $pageclass;

        return $this;
    }

    /**
     * Get pageclass.
     *
     * @return string
     */
    public function getPageclass()
    {
        return $this->pageclass;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Skeleton
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set keywords.
     *
     * @param string $keywords
     *
     * @return Skeleton
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Get keywords.
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set introtext.
     *
     * @param string $introtext
     *
     * @return Skeleton
     */
    public function setIntrotext($introtext)
    {
        $this->introtext = $introtext;

        return $this;
    }

    /**
     * Get introtext.
     *
     * @return string
     */
    public function getIntrotext()
    {
        return $this->introtext;
    }

    /**
     * Set intromediasize.
     *
     * @param string $intromediasize
     *
     * @return Skeleton
     */
    public function setIntromediasize($intromediasize)
    {
        $this->intromediasize = $intromediasize;

        return $this;
    }

    /**
     * Get intromediasize.
     *
     * @return string
     */
    public function getIntromediasize()
    {
        return $this->intromediasize;
    }

    /**
     * Set introclass.
     *
     * @param string $introclass
     *
     * @return Skeleton
     */
    public function setIntroclass($introclass)
    {
        $this->introclass = $introclass;

        return $this;
    }

    /**
     * Get introclass.
     *
     * @return string
     */
    public function getIntroclass()
    {
        return $this->introclass;
    }

    /**
     * Set pagetype.
     *
     * @param string $pagetype
     *
     * @return Skeleton
     */
    public function setPagetype($pagetype)
    {
        $this->pagetype = $pagetype;

        return $this;
    }

    /**
     * Get pagetype.
     *
     * @return string
     */
    public function getPagetype()
    {
        return $this->pagetype;
    }

    /**
     * Set author.
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     *
     * @return Skeleton
     */
    public function setAuthor(\Application\Sonata\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set introimage.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $introimage
     *
     * @return Skeleton
     */
    public function setIntroimage(\Application\Sonata\MediaBundle\Entity\Media $introimage = null)
    {
        $this->introimage = $introimage;

        return $this;
    }

    /**
     * Get introimage.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getIntroimage()
    {
        return $this->introimage;
    }

    /**
     * Set introvideo.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $introvideo
     *
     * @return Skeleton
     */
    public function setIntrovideo(\Application\Sonata\MediaBundle\Entity\Media $introvideo = null)
    {
        $this->introvideo = $introvideo;

        return $this;
    }

    /**
     * Get introvideo.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getIntrovideo()
    {
        return $this->introvideo;
    }

    /**
     * Add categories.
     *
     * @param \BardisCMS\CategoryBundle\Entity\Category $categories
     *
     * @return Skeleton
     */
    public function addCategory(\BardisCMS\CategoryBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories.
     *
     * @param \BardisCMS\CategoryBundle\Entity\Category $categories
     */
    public function removeCategory(\BardisCMS\CategoryBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add tags.
     *
     * @param \BardisCMS\TagBundle\Entity\Tag $tags
     *
     * @return Skeleton
     */
    public function addTag(\BardisCMS\TagBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags.
     *
     * @param \BardisCMS\TagBundle\Entity\Tag $tags
     */
    public function removeTag(\BardisCMS\TagBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add maincontentblocks.
     *
     * @param \BardisCMS\ContentBlockBundle\Entity\ContentBlock $maincontentblocks
     *
     * @return Skeleton
     */
    public function addMaincontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $maincontentblocks)
    {
        $this->maincontentblocks[] = $maincontentblocks;

        return $this;
    }

    /**
     * Remove maincontentblocks.
     *
     * @param \BardisCMS\ContentBlockBundle\Entity\ContentBlock $maincontentblocks
     */
    public function removeMaincontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $maincontentblocks)
    {
        $this->maincontentblocks->removeElement($maincontentblocks);
    }

    /**
     * Get maincontentblocks.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMaincontentblocks()
    {
        return $this->maincontentblocks;
    }

    /**
     * Add bannercontentblocks.
     *
     * @param \BardisCMS\ContentBlockBundle\Entity\ContentBlock $bannercontentblocks
     *
     * @return Skeleton
     */
    public function addBannercontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $bannercontentblocks)
    {
        $this->bannercontentblocks[] = $bannercontentblocks;

        return $this;
    }

    /**
     * Remove bannercontentblocks.
     *
     * @param \BardisCMS\ContentBlockBundle\Entity\ContentBlock $bannercontentblocks
     */
    public function removeBannercontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $bannercontentblocks)
    {
        $this->bannercontentblocks->removeElement($bannercontentblocks);
    }

    /**
     * Get bannercontentblocks.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBannercontentblocks()
    {
        return $this->bannercontentblocks;
    }

    /**
     * Add modalcontentblocks.
     *
     * @param \BardisCMS\ContentBlockBundle\Entity\ContentBlock $modalcontentblocks
     *
     * @return Skeleton
     */
    public function addModalcontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $modalcontentblocks)
    {
        $this->modalcontentblocks[] = $modalcontentblocks;

        return $this;
    }

    /**
     * Remove modalcontentblocks.
     *
     * @param \BardisCMS\ContentBlockBundle\Entity\ContentBlock $modalcontentblocks
     */
    public function removeModalcontentblock(\BardisCMS\ContentBlockBundle\Entity\ContentBlock $modalcontentblocks)
    {
        $this->modalcontentblocks->removeElement($modalcontentblocks);
    }

    /**
     * Get modalcontentblocks.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModalcontentblocks()
    {
        return $this->modalcontentblocks;
    }

    /**
     * Get dateLastModified.
     *
     * @return int
     */
    public function getDateLastModified()
    {
        return $this->dateLastModified;
    }

    /**
     * Set dateLastModified.
     *
     * @param int $dateLastModified
     *
     * @return Skeleton
     */
    public function setDateLastModified($dateLastModified)
    {
        $this->dateLastModified = $dateLastModified;

        return $this;
    }

    /**
     * toString Title.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->getTitle()) {
            return (string) $this->getTitle();
        }

        return (string) 'New Skeleton Item';
    }

    /**
     * Returns PublishState list.
     *
     * @return array
     */
    public static function getPublishStateList()
    {
        return array(
            self::STATUS_UNPUBLISHED => 'Unpublished',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_PREVIEW => 'Preview',
            self::STATUS_NONAUTHONLY => 'Anonymous Users Only',
            self::STATUS_AUTHONLY => 'Authenticated Users Only',
        );
    }

    /**
     * toString PublishState.
     *
     * @return string
     */
    public function getPublishStateAsString()
    {
        // Defining the string values of the publish states
        switch ($this->getPublishState()) {
            case self::STATUS_UNPUBLISHED: return 'Unpublished';
            case self::STATUS_PUBLISHED: return 'Published';
            case self::STATUS_PREVIEW: return 'Preview';
            case self::STATUS_NONAUTHONLY: return 'Anonymous Users Only';
            case self::STATUS_AUTHONLY: return 'Authenticated Users Only';
            default: return $this->getPublishState();
        }
    }

    /**
     * toString Pagetype.
     *
     * @return string
     */
    public function getPagetypeAsString()
    {
        // Defining the string values of the page types
        switch ($this->getPagetype()) {
            case 'skeleton_article': return 'Skeleton Article';
            case 'skeleton_filtered_list': return 'Skeleton Filtered Results';
            case 'skeleton_home': return 'Skeleton Homepage';
            default: return $this->getPagetype();
        }
    }
}
