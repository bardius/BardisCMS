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

use Doctrine\ORM\Mapping as ORM;

/**
 * BardisCMS\ContentBlockBundle\Entity\ContentSlide.
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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set imagefile.
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $imagefile
     *
     * @return ContentImage
     */
    public function setImagefile(\Application\Sonata\MediaBundle\Entity\Media $imagefile = null)
    {
        $this->imagefile = $imagefile;

        return $this;
    }

    /**
     * Get imagefile.
     *
     * @return Application\Sonata\MediaBundle\Entity\Media
     */
    public function getImagefile()
    {
        return $this->imagefile;
    }

    /**
     * Set imageLinkTitle.
     *
     * @param string $imageLinkTitle
     */
    public function setImageLinkTitle($imageLinkTitle)
    {
        $this->imageLinkTitle = $imageLinkTitle;

        return $this;
    }

    /**
     * Get imageLinkTitle.
     *
     * @return string
     */
    public function getImageLinkTitle()
    {
        return $this->imageLinkTitle;
    }

    /**
     * Set imageLinkURL.
     *
     * @param int $imageLinkURL
     *
     * @return ContentImage
     */
    public function setImageLinkURL($imageLinkURL)
    {
        $this->imageLinkURL = $imageLinkURL;

        return $this;
    }

    /**
     * Get imageLinkURL.
     *
     * @return int
     */
    public function getImageLinkURL()
    {
        return $this->imageLinkURL;
    }
}
