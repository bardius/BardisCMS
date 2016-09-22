<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\CategoryBundle\Entity;

use BardisCMS\BlogBundle\Entity\Blog;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BardisCMS\CategoryBundle\Entity\Category.
 *
 * @ORM\Table(name="categories")
 * @ORM\Entity
 */
class Category
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
    protected $categoryClass = null;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="categoryIcon", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $categoryIcon;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\PageBundle\Entity\Page", mappedBy="categories", cascade={"persist"})
     */
    protected $pages;

    /**
     * @ORM\ManyToMany(targetEntity="BardisCMS\BlogBundle\Entity\Blog", mappedBy="categories", cascade={"persist"})
     */
    protected $blogs;

    /**
     * @ORM\Column(name="date_last_modified", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $dateLastModified;

    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->blogs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
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
     * Set categoryClass.
     *
     * @param string $categoryClass
     *
     * @return Category
     */
    public function setCategoryClass($categoryClass)
    {
        $this->categoryClass = $categoryClass;

        return $this;
    }

    /**
     * Get categoryClass.
     *
     * @return string
     */
    public function getCategoryClass()
    {
        return $this->categoryClass;
    }

    /**
     * Set categoryIcon.
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $categoryIcon
     *
     * @return Category
     */
    public function setCategoryIcon(\Application\Sonata\MediaBundle\Entity\Media $categoryIcon = null)
    {
        $this->categoryIcon = $categoryIcon;

        return $this;
    }

    /**
     * Get categoryIcon.
     *
     * @return Application\Sonata\MediaBundle\Entity\Media
     */
    public function getCategoryIcon()
    {
        return $this->categoryIcon;
    }

    /**
     * Add blogs.
     *
     * @param BardisCMS\BlogBundle\Entity\Blog $blogs
     *
     * @return Category
     */
    public function addBlog(\BardisCMS\BlogBundle\Entity\Blog $blogs)
    {
        $this->blogs[] = $blogs;

        return $this;
    }

    /**
     * Remove blogs.
     *
     * @param BardisCMS\BlogBundle\Entity\Blog $blogs
     */
    public function removeBlog(\BardisCMS\BlogBundle\Entity\Blog $blogs)
    {
        $this->blogs->removeElement($blogs);
    }

    /**
     * Get Blog.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getBlogs()
    {
        return $this->blogs;
    }

    /**
     * Add pages.
     *
     * @param BardisCMS\PageBundle\Entity\Page $pages
     *
     * @return Category
     */
    public function addPage(\BardisCMS\PageBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;

        return $this;
    }

    /**
     * Remove pages.
     *
     * @param BardisCMS\PageBundle\Entity\Page $pages
     */
    public function removePage(\BardisCMS\PageBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages.
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPages()
    {
        return $this->pages;
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

        return (string) 'New Category';
    }
}
