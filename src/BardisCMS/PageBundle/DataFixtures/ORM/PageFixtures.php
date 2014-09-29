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
        $page404->setTitle('404 Error Page');
        $page404->setAuthor($manager->merge($this->getReference('admin')));
        $page404->setAlias('404');
        $page404->setShowPageTitle(1);
        $page404->setPublishState(1);
        $page404->setIntrotext('');
        $page404->setPagetype('404');
        $manager->persist($page404);

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
        $this->addReference('pagecontact', $pagecontact);
        $this->addReference('pagesitemap', $pagesitemap);
        $this->addReference('pagefiltered', $pagefiltered);
        $this->addReference('page1', $page1);
        $this->addReference('page2', $page2);
        $this->addReference('page3', $page3);
        $this->addReference('page4', $page4);
    }

    public function getOrder() {
        return 6;
    }

}