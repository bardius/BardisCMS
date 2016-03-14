<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BardisCMS\PageBundle\Entity\Page;

class PageFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $pagehome = new Page();
        $pagehome->setDate(new \DateTime());
        $pagehome->setTitle('Home');
        $pagehome->setAuthor($manager->merge($this->getReference('admin')));
        $pagehome->setAlias('index');
        $pagehome->setShowPageTitle(1);
        $pagehome->setPublishState(1);
        $pagehome->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $pagehome->setPagetype('homepage');
        $pagehome->addCategory($manager->merge($this->getReference('categoryHome')));
        $pagehome->addMaincontentblock($manager->merge($this->getReference('contentSampleHome')));
        $pagehome->addBannercontentblock($manager->merge($this->getReference('contentHomeSlide1')));
        $pagehome->addBannercontentblock($manager->merge($this->getReference('contentHomeSlide2')));
        $manager->persist($pagehome);

        $page404 = new Page();
        $page404->setDate(new \DateTime());
        $page404->setTitle('404 Error - Page not found');
        $page404->setAuthor($manager->merge($this->getReference('admin')));
        $page404->setAlias('404');
        $page404->setShowPageTitle(1);
        $page404->setPublishState(1);
        $page404->setIntrotext('');
        $page404->setPagetype('404');
        $manager->persist($page404);

        $page403 = new Page();
        $page403->setDate(new \DateTime());
        $page403->setTitle('403 Error - Unauthorised Access Forbidden');
        $page403->setAuthor($manager->merge($this->getReference('admin')));
        $page403->setAlias('403');
        $page403->setShowPageTitle(1);
        $page403->setPublishState(1);
        $page403->setIntrotext('');
        $page403->setPagetype('404');
        $manager->persist($page403);

        $page401 = new Page();
        $page401->setDate(new \DateTime());
        $page401->setTitle('401 Error - Unauthorized Access');
        $page401->setAuthor($manager->merge($this->getReference('admin')));
        $page401->setAlias('401');
        $page401->setShowPageTitle(1);
        $page401->setPublishState(1);
        $page401->setIntrotext('');
        $page401->setPagetype('404');
        $manager->persist($page401);

        $pagesitemap = new Page();
        $pagesitemap->setDate(new \DateTime());
        $pagesitemap->setTitle('Sitemap');
        $pagesitemap->setAuthor($manager->merge($this->getReference('admin')));
        $pagesitemap->setAlias('site-map');
        $pagesitemap->setShowPageTitle(1);
        $pagesitemap->setPublishState(1);
        $pagesitemap->setIntrotext('');
        $pagesitemap->setPagetype('sitemap');
        $manager->persist($pagesitemap);

        $pagefiltered = new Page();
        $pagefiltered->setDate(new \DateTime());
        $pagefiltered->setTitle('Page Filtered Listing');
        $pagefiltered->setAuthor($manager->merge($this->getReference('admin')));
        $pagefiltered->setAlias('tagged');
        $pagefiltered->setShowPageTitle(1);
        $pagefiltered->setPublishState(1);
        $pagefiltered->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $pagefiltered->setPagetype('page_tag_list');
        $pagefiltered->addCategory($manager->merge($this->getReference('categorySample')));
        $pagefiltered->addTag($manager->merge($this->getReference('tagSample1')));
        $manager->persist($pagefiltered);

        $pagecontact = new Page();
        $pagecontact->setDate(new \DateTime());
        $pagecontact->setTitle('Contact Page');
        $pagecontact->setAuthor($manager->merge($this->getReference('admin')));
        $pagecontact->setAlias('contact-page');
        $pagecontact->setShowPageTitle(1);
        $pagecontact->setPublishState(1);
        $pagecontact->setIntrotext('');
        $pagecontact->setPagetype('contact');
        $pagecontact->addMaincontentblock($manager->merge($this->getReference('contentSampleContact')));
        $manager->persist($pagecontact);

        $pageuser_profile = new Page();
        $pageuser_profile->setDate(new \DateTime());
        $pageuser_profile->setTitle('User Profile Page');
        $pageuser_profile->setAuthor($manager->merge($this->getReference('admin')));
        $pageuser_profile->setAlias('profile');
        $pageuser_profile->setShowPageTitle(1);
        $pageuser_profile->setPublishState(1);
        $pageuser_profile->setIntrotext('');
        $pageuser_profile->setPagetype('user_profile');
        $manager->persist($pageuser_profile);

        $pageuser_edit_profile = new Page();
        $pageuser_edit_profile->setDate(new \DateTime());
        $pageuser_edit_profile->setTitle('Edit User Profile Page');
        $pageuser_edit_profile->setAuthor($manager->merge($this->getReference('admin')));
        $pageuser_edit_profile->setAlias('edit-profile');
        $pageuser_edit_profile->setShowPageTitle(1);
        $pageuser_edit_profile->setPublishState(1);
        $pageuser_edit_profile->setIntrotext('');
        $pageuser_edit_profile->setPagetype('user_profile');
        $manager->persist($pageuser_edit_profile);

        $pageuser_edit_auth = new Page();
        $pageuser_edit_auth->setDate(new \DateTime());
        $pageuser_edit_auth->setTitle('Edit User Authentication Page');
        $pageuser_edit_auth->setAuthor($manager->merge($this->getReference('admin')));
        $pageuser_edit_auth->setAlias('edit-authentication');
        $pageuser_edit_auth->setShowPageTitle(1);
        $pageuser_edit_auth->setPublishState(1);
        $pageuser_edit_auth->setIntrotext('');
        $pageuser_edit_auth->setPagetype('user_profile');
        $manager->persist($pageuser_edit_auth);

        $pageuser_login = new Page();
        $pageuser_login->setDate(new \DateTime());
        $pageuser_login->setTitle('User Login Page');
        $pageuser_login->setAuthor($manager->merge($this->getReference('admin')));
        $pageuser_login->setAlias('login');
        $pageuser_login->setShowPageTitle(1);
        $pageuser_login->setPublishState(1);
        $pageuser_login->setIntrotext('');
        $pageuser_login->setPagetype('system_page');
        $manager->persist($pageuser_login);

        $pagepass_reset = new Page();
        $pagepass_reset->setDate(new \DateTime());
        $pagepass_reset->setTitle('Password Reset Page');
        $pagepass_reset->setAuthor($manager->merge($this->getReference('admin')));
        $pagepass_reset->setAlias('resetting/request');
        $pagepass_reset->setShowPageTitle(1);
        $pagepass_reset->setPublishState(1);
        $pagepass_reset->setIntrotext('');
        $pagepass_reset->setPagetype('system_page');
        $manager->persist($pagepass_reset);

        $pageresetting_send = new Page();
        $pageresetting_send->setDate(new \DateTime());
        $pageresetting_send->setTitle('Password Reset Email Already Send Page');
        $pageresetting_send->setAuthor($manager->merge($this->getReference('admin')));
        $pageresetting_send->setAlias('resetting/send-email');
        $pageresetting_send->setShowPageTitle(1);
        $pageresetting_send->setPublishState(1);
        $pageresetting_send->setIntrotext('');
        $pageresetting_send->setPagetype('system_page');
        $manager->persist($pageresetting_send);

        $pageresetting_check = new Page();
        $pageresetting_check->setDate(new \DateTime());
        $pageresetting_check->setTitle('Password Reset Email Send Page');
        $pageresetting_check->setAuthor($manager->merge($this->getReference('admin')));
        $pageresetting_check->setAlias('resetting/check-email');
        $pageresetting_check->setShowPageTitle(1);
        $pageresetting_check->setPublishState(1);
        $pageresetting_check->setIntrotext('');
        $pageresetting_check->setPagetype('system_page');
        $manager->persist($pageresetting_check);

        $pageresetting_reset = new Page();
        $pageresetting_reset->setDate(new \DateTime());
        $pageresetting_reset->setTitle('Password has been Reset Page');
        $pageresetting_reset->setAuthor($manager->merge($this->getReference('admin')));
        $pageresetting_reset->setAlias('resetting/reset');
        $pageresetting_reset->setShowPageTitle(1);
        $pageresetting_reset->setPublishState(1);
        $pageresetting_reset->setIntrotext('');
        $pageresetting_reset->setPagetype('system_page');
        $manager->persist($pageresetting_reset);

        $page_register = new Page();
        $page_register->setDate(new \DateTime());
        $page_register->setTitle('User Registration Page');
        $page_register->setAuthor($manager->merge($this->getReference('admin')));
        $page_register->setAlias('register');
        $page_register->setShowPageTitle(1);
        $page_register->setPublishState(1);
        $page_register->setIntrotext('');
        $page_register->setPagetype('system_page');
        $manager->persist($page_register);

        $page_register_confirmed = new Page();
        $page_register_confirmed->setDate(new \DateTime());
        $page_register_confirmed->setTitle('Registration Complete Page');
        $page_register_confirmed->setAuthor($manager->merge($this->getReference('admin')));
        $page_register_confirmed->setAlias('register/confirmed');
        $page_register_confirmed->setShowPageTitle(1);
        $page_register_confirmed->setPublishState(1);
        $page_register_confirmed->setIntrotext('');
        $page_register_confirmed->setPagetype('system_page');
        $manager->persist($page_register_confirmed);

        $page_pass_change = new Page();
        $page_pass_change->setDate(new \DateTime());
        $page_pass_change->setTitle('User Password Change Page');
        $page_pass_change->setAuthor($manager->merge($this->getReference('admin')));
        $page_pass_change->setAlias('user/password-change');
        $page_pass_change->setShowPageTitle(1);
        $page_pass_change->setPublishState(1);
        $page_pass_change->setIntrotext('');
        $page_pass_change->setPagetype('system_page');
        $manager->persist($page_pass_change);

        $page1 = new Page();
        $page1->setDate(new \DateTime());
        $page1->setTitle('Test Page 1');
        $page1->setAuthor($manager->merge($this->getReference('admin')));
        $page1->setAlias('test-page-1');
        $page1->setShowPageTitle(1);
        $page1->setPublishState(1);
        $page1->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $page1->setIntroimage($manager->merge($this->getReference('introImage1')));
        $page1->setPagetype('one_columned');
        $page1->addCategory($manager->merge($this->getReference('categoryHome')));
        $page1->addCategory($manager->merge($this->getReference('categorySample')));
        $page1->addTag($manager->merge($this->getReference('tagSample1')));
        $page1->addMaincontentblock($manager->merge($this->getReference('contentSample1')));
        $page1->addMaincontentblock($manager->merge($this->getReference('contentSample2')));
        $manager->persist($page1);

        $page2 = new Page();
        $page2->setDate(new \DateTime());
        $page2->setTitle('Test Page 2');
        $page2->setAuthor($manager->merge($this->getReference('admin')));
        $page2->setAlias('test-page-2');
        $page2->setShowPageTitle(1);
        $page2->setPublishState(1);
        $page2->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $page2->setIntroimage($manager->merge($this->getReference('introImage2')));
        $page2->setPagetype('two_columned');
        $page2->addCategory($manager->merge($this->getReference('categoryHome')));
        $page2->addCategory($manager->merge($this->getReference('categorySample')));
        $page2->addTag($manager->merge($this->getReference('tagSample1')));
        $manager->persist($page2);

        $page3 = new Page();
        $page3->setDate(new \DateTime());
        $page3->setTitle('Test Page 3');
        $page3->setAuthor($manager->merge($this->getReference('admin')));
        $page3->setAlias('test-page-3');
        $page3->setShowPageTitle(1);
        $page3->setPublishState(1);
        $page3->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $page3->setIntroimage($manager->merge($this->getReference('introImage3')));
        $page3->setPagetype('three_columned');
        $page3->addCategory($manager->merge($this->getReference('categoryHome')));
        $page3->addTag($manager->merge($this->getReference('tagSample1')));
        $manager->persist($page3);

        $page4 = new Page();
        $page4->setDate(new \DateTime());
        $page4->setTitle('Test Page 4');
        $page4->setAuthor($manager->merge($this->getReference('admin')));
        $page4->setAlias('test-page-4');
        $page4->setShowPageTitle(1);
        $page4->setPublishState(1);
        $page4->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $page4->setIntroimage($manager->merge($this->getReference('introImage4')));
        $page4->setPagetype('one_columned');
        $page4->addCategory($manager->merge($this->getReference('categoryHome')));
        $page4->addTag($manager->merge($this->getReference('tagSample1')));
        $manager->persist($page4);

        $manager->flush();

        $this->addReference('homepage', $pagehome);
        $this->addReference('404page', $page404);
        $this->addReference('403page', $page403);
        $this->addReference('page401', $page401);
        $this->addReference('pagecontact', $pagecontact);
        $this->addReference('pageuser_profile', $pageuser_profile);
        $this->addReference('pagesitemap', $pagesitemap);
        $this->addReference('pagefiltered', $pagefiltered);
        $this->addReference('page1', $page1);
        $this->addReference('page2', $page2);
        $this->addReference('page3', $page3);
        $this->addReference('page4', $page4);
        $this->addReference('pageuser_login', $pageuser_login);
        $this->addReference('pagepass_reset', $pagepass_reset);
        $this->addReference('pageresetting_send', $pageresetting_send);
        $this->addReference('pageresetting_check', $pageresetting_check);
        $this->addReference('pageresetting_reset', $pageresetting_reset);
        $this->addReference('page_register', $page_register);
        $this->addReference('page_register_confirmed', $page_register_confirmed);
        $this->addReference('page_pass_change', $page_pass_change);
        $this->addReference('pageuser_edit_profile', $pageuser_edit_profile);
        $this->addReference('pageuser_edit_auth', $pageuser_edit_auth);
    }

    public function getOrder() {
        return 9;
    }

}
