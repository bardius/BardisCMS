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

class MenuFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $menuHome = new Menu();
		$menuHome->setPage($manager->merge($this->getReference('homepage')));
        $menuHome->setTitle('Homepage');
        $menuHome->setMenuType('Page');
        $menuHome->setRoute('showPage');
        $menuHome->setAccessLevel(0);
        $menuHome->setParent(0);
        $menuHome->setMenuGroup('Main Menu');
        $menuHome->setPublishState(1);
        $menuHome->setOrdering(0);
		$manager->persist($menuHome);
		
        $menuSamplePage = new Menu();
		$menuSamplePage->setPage($manager->merge($this->getReference('page1')));
        $menuSamplePage->setTitle('Test Page 1');
        $menuSamplePage->setMenuType('Page');
        $menuSamplePage->setRoute('showPage');
        $menuSamplePage->setAccessLevel(0);
        $menuSamplePage->setParent(0);
        $menuSamplePage->setMenuGroup('Main Menu');
        $menuSamplePage->setPublishState(1);
        $menuSamplePage->setOrdering(1);
		$manager->persist($menuSamplePage);
		
        $menuBlogPage = new Menu();
		$menuBlogPage->setBlog($manager->merge($this->getReference('bloghome')));
        $menuBlogPage->setTitle('Blog Page');
        $menuBlogPage->setMenuType('Blog');
        $menuBlogPage->setRoute('showPage');
        $menuBlogPage->setAccessLevel(0);
        $menuBlogPage->setParent(0);
        $menuBlogPage->setMenuGroup('Main Menu');
        $menuBlogPage->setPublishState(1);
        $menuBlogPage->setOrdering(1);
		$manager->persist($menuBlogPage);
		
        $menuBlogPost = new Menu();
		$menuBlogPost->setBlog($manager->merge($this->getReference('blog1')));
        $menuBlogPost->setTitle('Sample Blog Post');
        $menuBlogPost->setMenuType('Blog');
        $menuBlogPost->setRoute('showPage');
        $menuBlogPost->setAccessLevel(0);
        $menuBlogPost->setParent(0);
        $menuBlogPost->setMenuGroup('Main Menu');
        $menuBlogPost->setPublishState(1);
        $menuBlogPost->setOrdering(1);
		$manager->persist($menuBlogPost);
		
        $menuContactPage = new Menu();
		$menuContactPage->setPage($manager->merge($this->getReference('pagecontact')));
        $menuContactPage->setTitle('Contact Page');
        $menuContactPage->setMenuType('Page');
        $menuContactPage->setRoute('showPage');
        $menuContactPage->setAccessLevel(0);
        $menuContactPage->setParent(0);
        $menuContactPage->setMenuGroup('Main Menu');
        $menuContactPage->setPublishState(1);
        $menuContactPage->setOrdering(5);
		$manager->persist($menuContactPage);
		
        $menuSitemapPage = new Menu();
		$menuSitemapPage->setPage($manager->merge($this->getReference('pagesitemap')));
        $menuSitemapPage->setTitle('Sitemap');
        $menuSitemapPage->setMenuType('Page');
        $menuSitemapPage->setRoute('showPage');
        $menuSitemapPage->setAccessLevel(0);
        $menuSitemapPage->setParent(0);
        $menuSitemapPage->setMenuGroup('Footer Menu');
        $menuSitemapPage->setPublishState(1);
        $menuSitemapPage->setOrdering(0);
		$manager->persist($menuSitemapPage);
		
        $manager->flush();
		
		$this->addReference('menuHome', $menuHome);
		$this->addReference('menuSamplePage', $menuSamplePage);
		$this->addReference('menuBlogPage', $menuBlogPage);
		$this->addReference('menuBlogPost', $menuBlogPost);		
		$this->addReference('menuContactPage', $menuContactPage);
		$this->addReference('menuSitemapPage', $menuSitemapPage);
    }
	
	public function getOrder()
    {
        return 9;
    }

}