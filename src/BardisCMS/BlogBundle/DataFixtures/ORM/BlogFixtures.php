<?php

/*
 * Blog Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BardisCMS\BlogBundle\Entity\Blog;

class BlogFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $bloghome = new Blog();
        $bloghome->setDate(new \DateTime());
        $bloghome->setTitle('Blog Home');
        $bloghome->setAuthor($manager->merge($this->getReference('admin')));
        $bloghome->setAlias('articles');
        $bloghome->setShowPageTitle(1);
        $bloghome->setPublishState(1);
        $bloghome->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $bloghome->setPagetype('blog_home');
		$manager->persist($bloghome);
		
        $blogfiltered = new Blog();
        $blogfiltered->setDate(new \DateTime());
        $blogfiltered->setTitle('Blog Filtered Listing');
        $blogfiltered->setAuthor($manager->merge($this->getReference('admin')));
        $blogfiltered->setAlias('tagged');
        $blogfiltered->setShowPageTitle(1);
        $blogfiltered->setPublishState(1);
        $blogfiltered->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $blogfiltered->setPagetype('blog_tag_list');
		$manager->persist($blogfiltered);
		
        $blog1 = new Blog();
        $blog1->setDate(new \DateTime());
        $blog1->setTitle('Test Blog Post 1');
        $blog1->setAuthor($manager->merge($this->getReference('admin')));
        $blog1->setAlias('test-blog-post-1');
        $blog1->setShowPageTitle(1);
        $blog1->setPublishState(1);
        $blog1->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $blog1->setIntroimage($manager->merge($this->getReference('introImage5')));
        $blog1->setPagetype('blog_article');
        $blog1->addCategory($manager->merge($this->getReference('categoryHome')));
        $blog1->addTag($manager->merge($this->getReference('tagSample1')));
        $blog1->addMaincontentblock($manager->merge($this->getReference('contentSampleBlog1')));
		$manager->persist($blog1);
		
        $blog2 = new Blog();
        $blog2->setDate(new \DateTime());
        $blog2->setTitle('Test Blog Post 2');
        $blog2->setAuthor($manager->merge($this->getReference('admin')));
        $blog2->setAlias('test-blog-post-2');
        $blog2->setShowPageTitle(1);
        $blog2->setPublishState(1);
        $blog2->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $blog2->setIntroimage($manager->merge($this->getReference('introImage6')));
        $blog2->setPagetype('blog_article');
        $blog2->addCategory($manager->merge($this->getReference('categoryHome')));
        $blog2->addTag($manager->merge($this->getReference('tagSample2')));
		$manager->persist($blog2);
		
        $blog3 = new Blog();
        $blog3->setDate(new \DateTime());
        $blog3->setTitle('Test Blog Post 3');
        $blog3->setAuthor($manager->merge($this->getReference('admin')));
        $blog3->setAlias('test-blog-post-3');
        $blog3->setShowPageTitle(1);
        $blog3->setPublishState(1);
        $blog3->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $blog3->setIntroimage($manager->merge($this->getReference('introImage7')));
        $blog3->setPagetype('blog_article');
        $blog3->addCategory($manager->merge($this->getReference('categoryHome')));
        $blog3->addTag($manager->merge($this->getReference('tagSample2')));
		$manager->persist($blog3);
		
        $blog4 = new Blog();
        $blog4->setDate(new \DateTime());
        $blog4->setTitle('Test Blog Post 4');
        $blog4->setAuthor($manager->merge($this->getReference('admin')));
        $blog4->setAlias('test-blog-post-4');
        $blog4->setShowPageTitle(1);
        $blog4->setPublishState(1);
        $blog4->setIntrotext('Lorem ipsum dolor sit amet, consectetur adipiscing eletra electrify denim vel ports.');
        $blog4->setIntroimage($manager->merge($this->getReference('introImage8')));
        $blog4->setPagetype('blog_article');
        $blog4->addCategory($manager->merge($this->getReference('categoryHome')));
        $blog4->addTag($manager->merge($this->getReference('tagSample1')));
		$manager->persist($blog4);
		
        $manager->flush();
		
		$this->addReference('bloghome', $bloghome);
		$this->addReference('blogfiltered', $blogfiltered);
		$this->addReference('blog1', $blog1);
        $this->addReference('blog2', $blog2);
        $this->addReference('blog3', $blog3);
        $this->addReference('blog4', $blog4);
    }
	
	public function getOrder()
    {
        return 7;
    }

}