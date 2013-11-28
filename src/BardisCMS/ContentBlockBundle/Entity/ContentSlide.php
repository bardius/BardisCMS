<?php
/*
 * ContentBlock Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\ContentBlockBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\MediaBundle\Entity\Media;
use BardisCMS\ContentBlockBundle\Entity\ContentBlock;


/**
 * BardisCMS\ContentBlockBundle\Entity\ContentSlide
 *
 * @ORM\Table(name="content_slides")
 * @ORM\Entity
 */
class ContentSlide
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="imagefile", onDelete="CASCADE")
     */ 
    protected $imagefile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */ 
    protected $imageLinkTitle = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */ 
    protected $imageLinkURL = null;

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
     * Set imagefile
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $imagefile
     * @return ContentImage
     */
    public function setImagefile(\Application\Sonata\MediaBundle\Entity\Media $imagefile = null)
    {
        $this->imagefile = $imagefile;
        return $this;
    }

    /**
     * Get imagefile
     *
     * @return Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getImagefile()
    {
        return $this->imagefile;
    }

    /**
     * Set imageLinkTitle
     *
     * @param string $imageLinkTitle
     */
    public function setImageLinkTitle($imageLinkTitle)
    {
        $this->imageLinkTitle = $imageLinkTitle;
    
        return $this;
    }

    /**
     * Get imageLinkTitle
     *
     * @return string 
     */
    public function getImageLinkTitle()
    {
        return $this->imageLinkTitle;
    }

    /**
     * Set imageLinkURL
     *
     * @param integer $imageLinkURL
     * @return ContentImage
     */
    public function setImageLinkURL($imageLinkURL)
    {
        $this->imageLinkURL = $imageLinkURL;
    
        return $this;
    }

    /**
     * Get imageLinkURL
     *
     * @return integer 
     */
    public function getImageLinkURL()
    {
        return $this->imageLinkURL;
    }
}