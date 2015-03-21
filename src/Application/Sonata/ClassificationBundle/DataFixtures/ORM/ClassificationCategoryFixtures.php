<?php

/*
 * Classification Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\ClassificationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\ClassificationBundle\Entity\Category;

class ClassificationCategoryFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $category1 = new Category();
        $category1->setContext($manager->merge($this->getReference('context4')));
        $category1->setName('default');
        $category1->setEnabled(1);
        $category1->setSlug('default');
        $category1->setDescription('Default Media Category');
        $category1->setPosition(null);
        $category1->setParent(null);
        $category1->setCreatedAt(new \DateTime());
        $category1->setUpdatedAt(new \DateTime());
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setContext($manager->merge($this->getReference('context1')));
        $category2->setName('intro');
        $category2->setEnabled(1);
        $category2->setSlug('intro');
        $category2->setDescription('Intro Media Category');
        $category2->setPosition(null);
        $category2->setParent(null);
        $category2->setCreatedAt(new \DateTime());
        $category2->setUpdatedAt(new \DateTime());
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setContext($manager->merge($this->getReference('context2')));
        $category3->setName('bgimage');
        $category3->setEnabled(1);
        $category3->setSlug('bgimage');
        $category3->setDescription('Background Image Media Category');
        $category3->setPosition(null);
        $category3->setParent(null);
        $category3->setCreatedAt(new \DateTime());
        $category3->setUpdatedAt(new \DateTime());
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setContext($manager->merge($this->getReference('context3')));
        $category4->setName('icons');
        $category4->setEnabled(1);
        $category4->setSlug('icons');
        $category4->setDescription('Icons Media Category');
        $category4->setPosition(null);
        $category4->setParent(null);
        $category4->setCreatedAt(new \DateTime());
        $category4->setUpdatedAt(new \DateTime());
        $manager->persist($category4);

        $manager->flush();

        $this->addReference('category1', $category1);
        $this->addReference('category2', $category2);
        $this->addReference('category3', $category3);
        $this->addReference('category4', $category4);
    }

    public function getOrder() {
        return 5;
    }

}
