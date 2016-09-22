<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\SettingsBundle\DataFixtures\ORM;

use BardisCMS\SettingsBundle\Entity\Settings;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

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
        $settings->setusersPerPage(20);
        $settings->setActivateSettings(1);
        $settings->getIsPublicProfilesAllowed(0);
        $manager->persist($settings);

        $manager->flush();

        $this->addReference('settings', $settings);
    }

    public function getOrder()
    {
        return 1;
    }
}
