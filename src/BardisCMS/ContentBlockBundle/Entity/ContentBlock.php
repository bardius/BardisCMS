<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\ContentBlockBundle\Entity;

use BardisCMS\PageBundle\Entity\Page;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BardisCMS\ContentBlockBundle\Entity\ContentBlock.
 *
 * @ORM\Table(name="content_blocks")
 * @ORM\Entity
 */
class ContentBlock
{
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
     * @ORM\Column(type="integer")
     */
    protected $publishedState;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $availability = 'page';

    /**
     * @ORM\Column(type="integer")
     */
    protected $showTitle;

    /**
     * @ORM\Column(type="integer")
     */
    protected $ordering;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $className = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $sizeClass = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $mediaSize = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $idName = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $contentType;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\PageBundle\Entity\Page", mappedBy="maincontentblocks", cascade={"persist"})
     * */
    protected $maincontents;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\BlogBundle\Entity\Blog", mappedBy="maincontentblocks", cascade={"persist"})
     * */
    protected $blog_maincontents;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\PageBundle\Entity\Page", mappedBy="secondarycontentblocks", cascade={"persist"})
     * */
    protected $secondarycontents;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\PageBundle\Entity\Page", mappedBy="extracontentblocks", cascade={"persist"})
     * */
    protected $extracontents;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\BlogBundle\Entity\Blog", mappedBy="extracontentblocks", cascade={"persist"})
     * */
    protected $blog_extracontents;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\PageBundle\Entity\Page", mappedBy="modalcontentblocks", cascade={"persist"})
     * */
    protected $modalcontents;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\BlogBundle\Entity\Blog", mappedBy="modalcontentblocks", cascade={"persist"})
     * */
    protected $blog_modalcontents;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\PageBundle\Entity\Page", mappedBy="bannercontentblocks", cascade={"persist"})
     * */
    protected $bannercontents;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\BlogBundle\Entity\Blog", mappedBy="bannercontentblocks", cascade={"persist"})
     * */
    protected $blog_bannercontents;

    /**
     * @ORM\ManyToMany(targetEntity="ContentImage", inversedBy="contentblocks", cascade={"all"}, orphanRemoval=true)
     * @ORM\JoinTable(name="content_blocks_images")
     */
    protected $imageFiles;

    /**
     * @ORM\OneToOne(targetEntity="ContentSlide", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="slide", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $slide;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="fileFile", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $fileFile;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="vimeo", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $vimeo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $htmlText = null;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="youtube", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $youtube;

    /**
     * @ORM\OneToOne(targetEntity="BardisCMS\ContentBlockBundle\Entity\ContentGlobalBlock", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="globalblock", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $globalblock;

    /**
     * @ORM\Column(name="date_last_modified", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $dateLastModified;

    public function __construct()
    {
        $this->maincontents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->secondarycontents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->extracontents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->modalcontents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bannercontents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blog_maincontents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blog_extracontents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blog_modalcontents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blog_bannercontents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->imagefiles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title.
     *
     * @param string $title
     *
     * @return ContentBlock
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
     * Set publishedState.
     *
     * @param int $publishedState
     *
     * @return ContentBlock
     */
    public function setPublishedState($publishedState)
    {
        $this->publishedState = $publishedState;

        return $this;
    }

    /**
     * Get publishedState.
     *
     * @return int
     */
    public function getPublishedState()
    {
        return $this->publishedState;
    }

    /**
     * Set availability.
     *
     * @param string $availability
     *
     * @return ContentBlock
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability.
     *
     * @return string
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set showTitle.
     *
     * @param int $showTitle
     *
     * @return ContentBlock
     */
    public function setShowTitle($showTitle)
    {
        $this->showTitle = $showTitle;

        return $this;
    }

    /**
     * Get showTitle.
     *
     * @return int
     */
    public function getShowTitle()
    {
        return $this->showTitle;
    }

    /**
     * Set ordering.
     *
     * @param int $ordering
     *
     * @return ContentBlock
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
     * Set className.
     *
     * @param string $className
     *
     * @return ContentBlock
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get className.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set sizeClass.
     *
     * @param string $sizeClass
     *
     * @return ContentBlock
     */
    public function setSizeClass($sizeClass)
    {
        $this->sizeClass = $sizeClass;

        return $this;
    }

    /**
     * Get sizeClass.
     *
     * @return string
     */
    public function getSizeClass()
    {
        return $this->sizeClass;
    }

    /**
     * Set mediaSize.
     *
     * @param string $mediaSize
     *
     * @return ContentBlock
     */
    public function setMediaSize($mediaSize)
    {
        $this->mediaSize = $mediaSize;

        return $this;
    }

    /**
     * Get mediaSize.
     *
     * @return string
     */
    public function getMediaSize()
    {
        return $this->mediaSize;
    }

    /**
     * Set idName.
     *
     * @param string $idName
     *
     * @return ContentBlock
     */
    public function setIdName($idName)
    {
        $this->idName = $idName;

        return $this;
    }

    /**
     * Get idName.
     *
     * @return string
     */
    public function getIdName()
    {
        return $this->idName;
    }

    /**
     * Set contentType.
     *
     * @param string $contentType
     *
     * @return ContentBlock
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set htmlText.
     *
     * @param string $htmlText
     *
     * @return ContentBlock
     */
    public function setHtmlText($htmlText)
    {
        $this->htmlText = $htmlText;

        return $this;
    }

    /**
     * Get htmlText.
     *
     * @return string
     */
    public function getHtmlText()
    {
        return $this->htmlText;
    }

    /**
     * Add maincontents.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $maincontents
     *
     * @return ContentBlock
     */
    public function addMaincontent(\BardisCMS\PageBundle\Entity\Page $maincontents)
    {
        $this->maincontents[] = $maincontents;

        return $this;
    }

    /**
     * Remove maincontents.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $maincontents
     */
    public function removeMaincontent(\BardisCMS\PageBundle\Entity\Page $maincontents)
    {
        $this->maincontents->removeElement($maincontents);
    }

    /**
     * Get maincontents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMaincontents()
    {
        return $this->maincontents;
    }

    /**
     * Add secondarycontents.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $secondarycontents
     *
     * @return ContentBlock
     */
    public function addSecondarycontent(\BardisCMS\PageBundle\Entity\Page $secondarycontents)
    {
        $this->secondarycontents[] = $secondarycontents;

        return $this;
    }

    /**
     * Remove secondarycontents.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $secondarycontents
     */
    public function removeSecondarycontent(\BardisCMS\PageBundle\Entity\Page $secondarycontents)
    {
        $this->secondarycontents->removeElement($secondarycontents);
    }

    /**
     * Get secondarycontents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecondarycontents()
    {
        return $this->secondarycontents;
    }

    /**
     * Add extracontents.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $extracontents
     *
     * @return ContentBlock
     */
    public function addExtracontent(\BardisCMS\PageBundle\Entity\Page $extracontents)
    {
        $this->extracontents[] = $extracontents;

        return $this;
    }

    /**
     * Remove extracontents.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $extracontents
     */
    public function removeExtracontent(\BardisCMS\PageBundle\Entity\Page $extracontents)
    {
        $this->extracontents->removeElement($extracontents);
    }

    /**
     * Get extracontents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExtracontents()
    {
        return $this->extracontents;
    }

    /**
     * Add modalcontents.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $modalcontents
     *
     * @return ContentBlock
     */
    public function addModalcontent(\BardisCMS\PageBundle\Entity\Page $modalcontents)
    {
        $this->modalcontents[] = $modalcontents;

        return $this;
    }

    /**
     * Remove modalcontents.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $modalcontents
     */
    public function removeModalcontent(\BardisCMS\PageBundle\Entity\Page $modalcontents)
    {
        $this->modalcontents->removeElement($modalcontents);
    }

    /**
     * Get modalcontents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModalcontents()
    {
        return $this->modalcontents;
    }

    /**
     * Add blog_extracontents.
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blogExtracontents
     *
     * @return ContentBlock
     */
    public function addBlogExtracontent(\BardisCMS\BlogBundle\Entity\Blog $blogExtracontents)
    {
        $this->blog_extracontents[] = $blogExtracontents;

        return $this;
    }

    /**
     * Remove blog_extracontents.
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blogExtracontents
     */
    public function removeBlogExtracontent(\BardisCMS\BlogBundle\Entity\Blog $blogExtracontents)
    {
        $this->blog_extracontents->removeElement($blogExtracontents);
    }

    /**
     * Get blog_extracontents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogExtracontents()
    {
        return $this->blog_extracontents;
    }

    /**
     * Add blog_maincontents.
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blogMaincontents
     *
     * @return ContentBlock
     */
    public function addBlogMaincontent(\BardisCMS\BlogBundle\Entity\Blog $blogMaincontents)
    {
        $this->blog_maincontents[] = $blogMaincontents;

        return $this;
    }

    /**
     * Remove blog_maincontents.
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blogMaincontents
     */
    public function removeBlogMaincontent(\BardisCMS\BlogBundle\Entity\Blog $blogMaincontents)
    {
        $this->blog_maincontents->removeElement($blogMaincontents);
    }

    /**
     * Get blog_maincontents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogMaincontents()
    {
        return $this->blog_maincontents;
    }

    /**
     * Add blog_modalcontents.
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blogModalcontents
     *
     * @return ContentBlock
     */
    public function addBlogModalcontent(\BardisCMS\BlogBundle\Entity\Blog $blogModalcontents)
    {
        $this->blog_modalcontents[] = $blogModalcontents;

        return $this;
    }

    /**
     * Remove blog_modalcontents.
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blogModalcontents
     */
    public function removeBlogModalcontent(\BardisCMS\BlogBundle\Entity\Blog $blogModalcontents)
    {
        $this->blog_modalcontents->removeElement($blogModalcontents);
    }

    /**
     * Get blog_modalcontents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogModalcontents()
    {
        return $this->blog_modalcontents;
    }

    /**
     * Add bannercontents.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $bannercontents
     *
     * @return ContentBlock
     */
    public function addBannercontent(\BardisCMS\PageBundle\Entity\Page $bannercontents)
    {
        $this->bannercontents[] = $bannercontents;

        return $this;
    }

    /**
     * Remove bannercontents.
     *
     * @param \BardisCMS\PageBundle\Entity\Page $bannercontents
     */
    public function removeBannercontent(\BardisCMS\PageBundle\Entity\Page $bannercontents)
    {
        $this->bannercontents->removeElement($bannercontents);
    }

    /**
     * Get bannercontents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBannercontents()
    {
        return $this->bannercontents;
    }

    /**
     * Add blog_bannercontents.
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blogBannercontents
     *
     * @return ContentBlock
     */
    public function addBlogBannercontent(\BardisCMS\BlogBundle\Entity\Blog $blogBannercontents)
    {
        $this->blog_bannercontents[] = $blogBannercontents;

        return $this;
    }

    /**
     * Remove blog_bannercontents.
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blogBannercontents
     */
    public function removeBlogBannercontent(\BardisCMS\BlogBundle\Entity\Blog $blogBannercontents)
    {
        $this->blog_bannercontents->removeElement($blogBannercontents);
    }

    /**
     * Get blog_bannercontents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBlogBannercontents()
    {
        return $this->blog_bannercontents;
    }

    /**
     * Add imageFiles.
     *
     * @param \BardisCMS\ContentBlockBundle\Entity\ContentImage $imageFiles
     *
     * @return ContentBlock
     */
    public function addImageFile(\BardisCMS\ContentBlockBundle\Entity\ContentImage $imageFiles)
    {
        $this->imageFiles[] = $imageFiles;

        return $this;
    }

    /**
     * Remove imageFiles.
     *
     * @param \BardisCMS\ContentBlockBundle\Entity\ContentImage $imageFiles
     */
    public function removeImageFile(\BardisCMS\ContentBlockBundle\Entity\ContentImage $imageFiles)
    {
        $this->imageFiles->removeElement($imageFiles);
    }

    /**
     * Get imageFiles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImageFiles()
    {
        return $this->imageFiles;
    }

    /**
     * Set slide.
     *
     * @param \BardisCMS\ContentBlockBundle\Entity\ContentSlide $slide
     *
     * @return ContentBlock
     */
    public function setSlide(\BardisCMS\ContentBlockBundle\Entity\ContentSlide $slide = null)
    {
        $this->slide = $slide;

        return $this;
    }

    /**
     * Get slide.
     *
     * @return \BardisCMS\ContentBlockBundle\Entity\ContentSlide
     */
    public function getSlide()
    {
        return $this->slide;
    }

    /**
     * Set fileFile.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $fileFile
     *
     * @return ContentBlock
     */
    public function setFileFile(\Application\Sonata\MediaBundle\Entity\Media $fileFile = null)
    {
        $this->fileFile = $fileFile;

        return $this;
    }

    /**
     * Get fileFile.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getFileFile()
    {
        return $this->fileFile;
    }

    /**
     * Set vimeo.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $vimeo
     *
     * @return ContentBlock
     */
    public function setVimeo(\Application\Sonata\MediaBundle\Entity\Media $vimeo = null)
    {
        $this->vimeo = $vimeo;

        return $this;
    }

    /**
     * Get vimeo.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getVimeo()
    {
        return $this->vimeo;
    }

    /**
     * Set youtube.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $youtube
     *
     * @return ContentBlock
     */
    public function setYoutube(\Application\Sonata\MediaBundle\Entity\Media $youtube = null)
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * Get youtube.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * Set globalblock.
     *
     * @param \BardisCMS\ContentBlockBundle\Entity\ContentGlobalBlock $globalblock
     *
     * @return ContentBlock
     */
    public function setGlobalblock(\BardisCMS\ContentBlockBundle\Entity\ContentGlobalBlock $globalblock = null)
    {
        $this->globalblock = $globalblock;

        return $this;
    }

    /**
     * Get globalblock.
     *
     * @return \BardisCMS\ContentBlockBundle\Entity\ContentGlobalBlock
     */
    public function getGlobalblock()
    {
        return $this->globalblock;
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
     * toString Title.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->getTitle()) {
            return (string) $this->getTitle();
        }

        return (string) 'New Content Block';
    }

    /**
     * toString PublishState.
     *
     * @return string
     */
    public function getPublishStateAsString()
    {
        switch ($this->getPublishedState()) {
            case 0: return 'Unpublished';
            case 1: return 'Published';
        }
    }

    /**
     * toString ShowTitle.
     *
     * @return string
     */
    public function getShowTitleAsString()
    {
        switch ($this->getShowTitle()) {
            case 0: return 'Hidden';
            case 1: return 'Visible';
        }
    }

    /**
     * toString ShowTitle.
     *
     * @return string
     */
    public function getAvailabilityAsString()
    {
        switch ($this->getAvailability()) {
            case 'page': return 'Page Only';
            case 'global': return 'Global';
        }
    }

    /**
     * toString ShowTitle.
     *
     * @return string
     */
    public function getContentTypeAsString()
    {
        switch ($this->getContentType()) {
            case 'html': return 'Text/HTML';
            case 'image': return 'Image';
            case 'file': return 'File Attachment';
            case 'youtube': return 'YouTube Video';
            case 'vimeo': return 'Vimeo Video';
            case 'slide': return 'Banner Slide';
            case 'contact': return 'Contact Form';
            case 'globalblock': return 'Global Content Block';
        }
    }
}
