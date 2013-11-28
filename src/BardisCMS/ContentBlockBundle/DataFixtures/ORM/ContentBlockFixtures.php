<?php

/*
 * ContentBlock Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\ContentBlockBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BardisCMS\ContentBlockBundle\Entity\ContentBlock;

class ContentBlockFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
		
        $contentSampleHome = new ContentBlock();
        $contentSampleHome->setTitle('Sample Content Home');
        $contentSampleHome->setPublishedState(1);
        $contentSampleHome->setAvailability('page');
        $contentSampleHome->setShowTitle(1);
        $contentSampleHome->setOrdering(1);
        $contentSampleHome->setSizeClass('large-12');
        $contentSampleHome->setContentType('html');
		$contentSampleHome->setHtmlText('<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>');
		$manager->persist($contentSampleHome);
		
        $contentSample1 = new ContentBlock();
        $contentSample1->setTitle('Sample Content 1');
        $contentSample1->setPublishedState(1);
        $contentSample1->setAvailability('page');
        $contentSample1->setShowTitle(1);
        $contentSample1->setOrdering(1);
        $contentSample1->setClassName('sampleClassname');
        $contentSample1->setSizeClass('large-12');
        $contentSample1->setIdName('sampleId');
        $contentSample1->setContentType('html');
		$contentSample1->setHtmlText('<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>');
		$manager->persist($contentSample1);
		
        $contentSample2 = new ContentBlock();
        $contentSample2->setTitle('Sample Content 2');
        $contentSample2->setPublishedState(1);
        $contentSample2->setAvailability('page');
        $contentSample2->setShowTitle(1);
        $contentSample2->setOrdering(2);
        $contentSample2->setSizeClass('large-12');
        $contentSample2->setContentType('html');
		$contentSample2->setHtmlText('<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>');
		$manager->persist($contentSample2);
		
        $contentSampleContact = new ContentBlock();
        $contentSampleContact->setTitle('Sample Contact Form');
        $contentSampleContact->setPublishedState(1);
        $contentSampleContact->setAvailability('page');
        $contentSampleContact->setShowTitle(1);
        $contentSampleContact->setOrdering(1);
        $contentSampleContact->setSizeClass('large-12');
        $contentSampleContact->setContentType('contact');
		$manager->persist($contentSampleContact);
		
        $contentSampleBlog1 = new ContentBlock();
        $contentSampleBlog1->setTitle('Sample Blog Content 1');
        $contentSampleBlog1->setPublishedState(1);
        $contentSampleBlog1->setAvailability('page');
        $contentSampleBlog1->setShowTitle(1);
        $contentSample1->setOrdering(1);
        $contentSampleBlog1->setClassName('sampleClassname');
        $contentSampleBlog1->setSizeClass('large-12');
        $contentSampleBlog1->setIdName('sampleId');
        $contentSampleBlog1->setContentType('html');
		$contentSampleBlog1->setHtmlText('<p>Quisque non arcu id ipsum imperdiet ultricies pharetra eu nibh. Etiam eros lectus, ullamcorper et congue in, lobortis sit amet lectus. In fermentum quam in arcu sodales, id varius est placerat. Fusce a dictum mi. Aliquam accumsan diam eget rutrum tincidunt. Nullam massa metus, placerat quis mattis nec</p>');
		$manager->persist($contentSampleBlog1);
		
        $manager->flush();
		
		$this->addReference('contentSampleHome', $contentSampleHome);	
		$this->addReference('contentSample1', $contentSample1);	
		$this->addReference('contentSample2', $contentSample2);
		$this->addReference('contentSampleContact', $contentSampleContact);	
		$this->addReference('contentSampleBlog1', $contentSampleBlog1);
    }
	
	public function getOrder()
    {
        return 4;
    }

}