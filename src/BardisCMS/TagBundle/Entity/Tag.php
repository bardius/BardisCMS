<?php
/*
 * Tag Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */
namespace BardisCMS\TagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use BardisCMS\PageBundle\Entity\Page;
use BardisCMS\BlogBundle\Entity\Blog;
use Application\Sonata\MediaBundle\Entity\Media;


/**
 * BardisCMS\TagBundle\Entity\Tag
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity
 */
class Tag
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */ 
    protected $tagCategory = null;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="tagIcon", referencedColumnName="id", onDelete="SET NULL")
     */ 
    protected $tagIcon;

   /**
    * @ORM\ManyToMany(targetEntity="BardisCMS\PageBundle\Entity\Page", mappedBy="tags", cascade={"persist"})
    */
    protected $pages;

   /**
    * @ORM\ManyToMany(targetEntity="BardisCMS\BlogBundle\Entity\Blog", mappedBy="tags", cascade={"persist"})
    */
    protected $blogs;

    public function __construct()
    {
        $this->pages    = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blogs    = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Tag
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set tagCategory
     *
     * @param string $tagCategory
     * @return Tag
     */
    public function setTagCategory($tagCategory)
    {
        $this->tagCategory = $tagCategory;
    
        return $this;
    }

    /**
     * Get tagCategory
     *
     * @return string 
     */
    public function getTagCategory()
    {
        return $this->tagCategory;
    }

    /**
     * Set tagIcon
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $tagIcon
     * @return Tag
     */
    public function setTagIcon(\Application\Sonata\MediaBundle\Entity\Media $tagIcon = null)
    {
        $this->tagIcon = $tagIcon;
    
        return $this;
    }

    /**
     * Get tagIcon
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getTagIcon()
    {
        return $this->tagIcon;
    }

    /**
     * Add pages
     *
     * @param \BardisCMS\PageBundle\Entity\Page $pages
     * @return Tag
     */
    public function addPage(\BardisCMS\PageBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;
    
        return $this;
    }

    /**
     * Remove pages
     *
     * @param \BardisCMS\PageBundle\Entity\Page $pages
     */
    public function removePage(\BardisCMS\PageBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Add blogs
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blogs
     * @return Tag
     */
    public function addBlog(\BardisCMS\BlogBundle\Entity\Blog $blogs)
    {
        $this->blogs[] = $blogs;
    
        return $this;
    }

    /**
     * Remove blogs
     *
     * @param \BardisCMS\BlogBundle\Entity\Blog $blogs
     */
    public function removeBlog(\BardisCMS\BlogBundle\Entity\Blog $blogs)
    {
        $this->blogs->removeElement($blogs);
    }

    /**
     * Get blogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlogs()
    {
        return $this->blogs;
    }
	
    /**
     * toString Title
     *
     * @return string 
     */
    public function __toString()
    {
		if($this->getTitle()){
			return (string)$this->getTitle();			
		}
		else{
			return (string)'New Tag';
		}
    }
}