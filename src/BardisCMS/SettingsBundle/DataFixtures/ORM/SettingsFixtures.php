<?php

/*
 * Settings Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\SettingsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BardisCMS\SettingsBundle\Entity\Settings;

class SettingsFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $settings = new Settings();
        $settings->setMetaDescription('Default Meta Description');
        $settings->setMetaKeywords('Default Meta Keywords');
        $settings->setFromTitle('Owner');
        $settings->setWebsiteTitle('Website Title');
        $settings->setWebsiteAuthor('Author');
        $settings->setUseWebsiteAuthor(1);
        $settings->setEnableGoogleAnalytics(0);
        $settings->setGoogleAnalyticsId('UA-XXX-XXXXX');
        $settings->setEmailSender('george@bardis.info');
        $settings->setEmailRecepient('george@bardis.info');
        $settings->setItemsPerPage(2);
        $settings->setBlogItemsPerPage(2);
        $settings->setActivateSettings(1);
		$manager->persist($settings);
		
        $manager->flush();
		
		$this->addReference('settings', $settings);
    }
	
	public function getOrder()
    {
        return 1;
    }

}