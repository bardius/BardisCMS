<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\CategoryBundle\DataFixtures\ORM;

use BardisCMS\CategoryBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $categoryHome = new Category();
        $categoryHome->setTitle('Homepage');
        $manager->persist($categoryHome);

        $categoryNews = new Category();
        $categoryNews->setTitle('News');
        $categoryNews->setCategoryClass('news');
        $manager->persist($categoryNews);

        $categoryEvents = new Category();
        $categoryEvents->setTitle('Events');
        $categoryEvents->setCategoryClass('events');
        $manager->persist($categoryEvents);

        $categorySample = new Category();
        $categorySample->setTitle('Sample Category');
        $categorySample->setCategoryClass('featured-category');
        $manager->persist($categorySample);

        $manager->flush();

        $this->addReference('categoryHome', $categoryHome);
        $this->addReference('categoryNews', $categoryNews);
        $this->addReference('categoryEvents', $categoryEvents);
        $this->addReference('categorySample', $categorySample);
    }

    public function getOrder()
    {
        return 2;
    }
}
