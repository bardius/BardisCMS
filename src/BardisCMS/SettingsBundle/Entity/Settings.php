<?php
/*
 * Settings Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */
namespace BardisCMS\SettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * BardisCMS\SettingsBundle\Entity\Settings
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity(repositoryClass="BardisCMS\SettingsBundle\Repository\SettingsRepository")
 */
class Settings
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $metaDescription = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */ 
    protected $metaKeywords = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */ 
    protected $fromTitle = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */ 
    protected $websiteTitle = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */ 
    protected $websiteAuthor = null;

    /**
     * @ORM\Column(type="boolean")
     */ 
    protected $useWebsiteAuthor = true;

    /**
     * @ORM\Column(type="boolean")
     */ 
    protected $enableGoogleAnalytics = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */ 
    protected $googleAnalyticsId = null;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */ 
    protected $emailSender = null;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */ 
    protected $emailRecepient = null;
    
    /**
     * @ORM\Column(type="integer", length=8)
     */ 
    protected $itemsPerPage = 10;
    
    /**
     * @ORM\Column(type="integer", length=8)
     */ 
    protected $blogItemsPerPage = 10;

    /**
     * @ORM\Column(type="boolean")
     */ 
    protected $activateSettings = true;

    
    

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
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return Settings
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    
        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return Settings
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    
        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set fromTitle
     *
     * @param string $fromTitle
     * @return Settings
     */
    public function setFromTitle($fromTitle)
    {
        $this->fromTitle = $fromTitle;
    
        return $this;
    }

    /**
     * Get fromTitle
     *
     * @return string 
     */
    public function getFromTitle()
    {
        return $this->fromTitle;
    }

    /**
     * Set websiteTitle
     *
     * @param string $websiteTitle
     * @return Settings
     */
    public function setWebsiteTitle($websiteTitle)
    {
        $this->websiteTitle = $websiteTitle;
    
        return $this;
    }

    /**
     * Get websiteTitle
     *
     * @return string 
     */
    public function getWebsiteTitle()
    {
        return $this->websiteTitle;
    }

    /**
     * Set websiteAuthor
     *
     * @param string $websiteAuthor
     * @return Settings
     */
    public function setWebsiteAuthor($websiteAuthor)
    {
        $this->websiteAuthor = $websiteAuthor;
    
        return $this;
    }

    /**
     * Get websiteAuthor
     *
     * @return string 
     */
    public function getWebsiteAuthor()
    {
        return $this->websiteAuthor;
    }

    /**
     * Set useWebsiteAuthor
     *
     * @param boolean $useWebsiteAuthor
     * @return Settings
     */
    public function setUseWebsiteAuthor($useWebsiteAuthor)
    {
        $this->useWebsiteAuthor = $useWebsiteAuthor;
    
        return $this;
    }

    /**
     * Get useWebsiteAuthor
     *
     * @return boolean 
     */
    public function getUseWebsiteAuthor()
    {
        return $this->useWebsiteAuthor;
    }

    /**
     * Set enableGoogleAnalytics
     *
     * @param boolean $enableGoogleAnalytics
     * @return Settings
     */
    public function setEnableGoogleAnalytics($enableGoogleAnalytics)
    {
        $this->enableGoogleAnalytics = $enableGoogleAnalytics;
    
        return $this;
    }

    /**
     * Get enableGoogleAnalytics
     *
     * @return boolean 
     */
    public function getEnableGoogleAnalytics()
    {
        return $this->enableGoogleAnalytics;
    }

    /**
     * Set googleAnalyticsId
     *
     * @param string $googleAnalyticsId
     * @return Settings
     */
    public function setGoogleAnalyticsId($googleAnalyticsId)
    {
        $this->googleAnalyticsId = $googleAnalyticsId;
    
        return $this;
    }

    /**
     * Get googleAnalyticsId
     *
     * @return string 
     */
    public function getGoogleAnalyticsId()
    {
        return $this->googleAnalyticsId;
    }

    /**
     * Set emailSender
     *
     * @param string $emailSender
     * @return Settings
     */
    public function setEmailSender($emailSender)
    {
        $this->emailSender = $emailSender;
    
        return $this;
    }

    /**
     * Get emailSender
     *
     * @return string 
     */
    public function getEmailSender()
    {
        return $this->emailSender;
    }

    /**
     * Set emailRecepient
     *
     * @param string $emailRecepient
     * @return Settings
     */
    public function setEmailRecepient($emailRecepient)
    {
        $this->emailRecepient = $emailRecepient;
    
        return $this;
    }

    /**
     * Get emailRecepient
     *
     * @return string 
     */
    public function getEmailRecepient()
    {
        return $this->emailRecepient;
    }
    
    /**
     * Set itemsPerPage
     *
     * @param string $itemsPerPage
     * @return Settings
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    
        return $this;
    }

    /**
     * Get itemsPerPage
     *
     * @return string 
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }
    
    /**
     * Set blogItemsPerPage
     *
     * @param string $blogItemsPerPage
     * @return Settings
     */
    public function setBlogItemsPerPage($blogItemsPerPage)
    {
        $this->blogItemsPerPage = $blogItemsPerPage;
    
        return $this;
    }

    /**
     * Get blogItemsPerPage
     *
     * @return string 
     */
    public function getBlogItemsPerPage()
    {
        return $this->blogItemsPerPage;
    }
    
    

    /**
     * Set activateSettings
     *
     * @param boolean $activateSettings
     * @return Settings
     */
    public function setActivateSettings($activateSettings)
    {
        $this->activateSettings = $activateSettings;
    
        return $this;
    }

    /**
     * Get activateSettings
     *
     * @return boolean 
     */
    public function getActivateSettings()
    {
        return $this->activateSettings;
    }    
    
    public function __toString()
    {
        return (string)"Website Settings";
    }
}