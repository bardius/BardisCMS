<?php

/*
 * Menu Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\MenuBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BardisCMS\MenuBundle\Entity\Menu;

class MenuFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $menuHome = new Menu();
        $menuHome->setPage($manager->merge($this->getReference('homepage')));
        $menuHome->setTitle('Homepage');
        $menuHome->setMenuType(Menu::TYPE_PAGE);
        $menuHome->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuHome->setAccessLevel(Menu::STATUS_PUBLIC);
        $menuHome->setParent(0);
        $menuHome->setMenuGroup(Menu::GROUP_MAIN);
        $menuHome->setPublishState(Menu::STATE_PUBLISHED);
        $menuHome->setOrdering(0);
        $manager->persist($menuHome);

        $menuBlog = new Menu();
        $menuBlog->setBlog($manager->merge($this->getReference('bloghome')));
        $menuBlog->setTitle('Blog');
        $menuBlog->setMenuType(Menu::TYPE_BLOG);
        $menuBlog->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuBlog->setAccessLevel(Menu::STATUS_PUBLIC);
        $menuBlog->setParent(0);
        $menuBlog->setMenuGroup(Menu::GROUP_MAIN);
        $menuBlog->setPublishState(Menu::STATE_PUBLISHED);
        $menuBlog->setOrdering(2);
        $manager->persist($menuBlog);

        $menuEvents = new Menu();
        $menuEvents->setBlog($manager->merge($this->getReference('blogevents')));
        $menuEvents->setTitle('Events');
        $menuEvents->setMenuType(Menu::TYPE_BLOG);
        $menuEvents->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuEvents->setAccessLevel(Menu::STATUS_PUBLIC);
        $menuEvents->setParent(0);
        $menuEvents->setMenuGroup(Menu::GROUP_MAIN);
        $menuEvents->setPublishState(Menu::STATE_PUBLISHED);
        $menuEvents->setOrdering(3);
        $manager->persist($menuEvents);

        $menuNews = new Menu();
        $menuNews->setBlog($manager->merge($this->getReference('blognews')));
        $menuNews->setTitle('News');
        $menuNews->setMenuType(Menu::TYPE_BLOG);
        $menuNews->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuNews->setAccessLevel(Menu::STATUS_PUBLIC);
        $menuNews->setParent(0);
        $menuNews->setMenuGroup(Menu::GROUP_MAIN);
        $menuNews->setPublishState(Menu::STATE_PUBLISHED);
        $menuNews->setOrdering(4);
        $manager->persist($menuNews);

        $menuSamplePage1 = new Menu();
        $menuSamplePage1->setPage($manager->merge($this->getReference('page2')));
        $menuSamplePage1->setTitle('Sports');
        $menuSamplePage1->setMenuType(Menu::TYPE_PAGE);
        $menuSamplePage1->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuSamplePage1->setAccessLevel(Menu::STATUS_PUBLIC);
        $menuSamplePage1->setParent(0);
        $menuSamplePage1->setMenuGroup(Menu::GROUP_MAIN);
        $menuSamplePage1->setPublishState(Menu::STATE_PUBLISHED);
        $menuSamplePage1->setOrdering(5);
        $manager->persist($menuSamplePage1);

        $menuSamplePage2 = new Menu();
        $menuSamplePage2->setPage($manager->merge($this->getReference('page1')));
        $menuSamplePage2->setTitle('E-Magazine');
        $menuSamplePage2->setMenuType(Menu::TYPE_PAGE);
        $menuSamplePage2->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuSamplePage2->setAccessLevel(Menu::STATUS_PUBLIC);
        $menuSamplePage2->setParent(0);
        $menuSamplePage2->setMenuGroup(Menu::GROUP_MAIN);
        $menuSamplePage2->setPublishState(Menu::STATE_PUBLISHED);
        $menuSamplePage2->setOrdering(6);
        $manager->persist($menuSamplePage2);

        $menuContactPage = new Menu();
        $menuContactPage->setPage($manager->merge($this->getReference('pagecontact')));
        $menuContactPage->setTitle('Contact Us');
        $menuContactPage->setMenuType(Menu::TYPE_PAGE);
        $menuContactPage->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuContactPage->setAccessLevel(Menu::STATUS_PUBLIC);
        $menuContactPage->setParent(0);
        $menuContactPage->setMenuGroup(Menu::GROUP_MAIN);
        $menuContactPage->setPublishState(Menu::STATE_PUBLISHED);
        $menuContactPage->setOrdering(7);
        $manager->persist($menuContactPage);

        $menuLoginPage = new Menu();
        $menuLoginPage->setPage($manager->merge($this->getReference('pageuser_login')));
        $menuLoginPage->setTitle('Login');
        $menuLoginPage->setMenuType(Menu::TYPE_PAGE);
        $menuLoginPage->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuLoginPage->setAccessLevel(Menu::STATUS_NONAUTHONLY);
        $menuLoginPage->setParent(0);
        $menuLoginPage->setMenuGroup(Menu::GROUP_MAIN);
        $menuLoginPage->setPublishState(Menu::STATE_PUBLISHED);
        $menuLoginPage->setOrdering(8);
        $manager->persist($menuLoginPage);

        $menuProfilePage = new Menu();
        $menuProfilePage->setPage($manager->merge($this->getReference('pageuser_profile')));
        $menuProfilePage->setTitle('Profile');
        $menuProfilePage->setMenuType(Menu::TYPE_PAGE);
        $menuProfilePage->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuProfilePage->setAccessLevel(Menu::STATUS_AUTHONLY);
        $menuProfilePage->setParent(0);
        $menuProfilePage->setMenuGroup(Menu::GROUP_MAIN);
        $menuProfilePage->setPublishState(Menu::STATE_PUBLISHED);
        $menuProfilePage->setOrdering(8);
        $manager->persist($menuProfilePage);

        $menuSitemapPage = new Menu();
        $menuSitemapPage->setPage($manager->merge($this->getReference('pagesitemap')));
        $menuSitemapPage->setTitle('Sitemap');
        $menuSitemapPage->setMenuType(Menu::TYPE_PAGE);
        $menuSitemapPage->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuSitemapPage->setAccessLevel(Menu::STATUS_PUBLIC);
        $menuSitemapPage->setParent(0);
        $menuSitemapPage->setMenuGroup(Menu::GROUP_FOOTER);
        $menuSitemapPage->setPublishState(Menu::STATE_PUBLISHED);
        $menuSitemapPage->setOrdering(0);
        $manager->persist($menuSitemapPage);

        $manager->flush();

        $this->addReference('menuHome', $menuHome);
        $this->addReference('menuSamplePage2', $menuSamplePage1);
        $this->addReference('menuSamplePage', $menuSamplePage2);
        $this->addReference('menuBlog', $menuBlog);
        $this->addReference('menuNews', $menuNews);
        $this->addReference('menuEvents', $menuEvents);
        $this->addReference('menuContactPage', $menuContactPage);
        $this->addReference('menuLoginPage', $menuLoginPage);
        $this->addReference('menuProfilePage', $menuProfilePage);
        $this->addReference('menuSitemapPage', $menuSitemapPage);
    }

    public function getOrder() {
        return 16;
    }

}
