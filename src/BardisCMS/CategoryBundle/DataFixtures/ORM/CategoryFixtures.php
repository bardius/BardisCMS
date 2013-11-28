<?php

/*
 * Category Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\CategoryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BardisCMS\CategoryBundle\Entity\Category;

class CategoryFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $categoryHome = new Category();
        $categoryHome->setTitle('Homepage');
		$manager->persist($categoryHome);
		
        $categorySample = new Category();
        $categorySample->setTitle('Sample Category');
        $categorySample->setCategoryClass('featured-category');
		$manager->persist($categorySample);
		
        $manager->flush();
		
		$this->addReference('categoryHome', $categoryHome);		
		$this->addReference('categorySample', $categorySample);
    }
	
	public function getOrder()
    {
        return 2;
    }

}