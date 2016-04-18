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
        $category1->setContext($manager->merge($this->getReference('context0')));
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

        $category5 = new Category();
        $category5->setContext($manager->merge($this->getReference('context4')));
        $category5->setName('user_avatar');
        $category5->setEnabled(1);
        $category5->setSlug('user_avatar');
        $category5->setDescription('User Avatars Category');
        $category5->setPosition(null);
        $category5->setParent(null);
        $category5->setCreatedAt(new \DateTime());
        $category5->setUpdatedAt(new \DateTime());
        $manager->persist($category5);

        $category6 = new Category();
        $category6->setContext($manager->merge($this->getReference('context5')));
        $category6->setName('user_hero');
        $category6->setEnabled(1);
        $category6->setSlug('user_hero');
        $category6->setDescription('User Hero Images Category');
        $category6->setPosition(null);
        $category6->setParent(null);
        $category6->setCreatedAt(new \DateTime());
        $category6->setUpdatedAt(new \DateTime());
        $manager->persist($category6);

        $manager->flush();

        $this->addReference('category1', $category1);
        $this->addReference('category2', $category2);
        $this->addReference('category3', $category3);
        $this->addReference('category4', $category4);
        $this->addReference('category5', $category5);
        $this->addReference('category6', $category6);
    }

    public function getOrder() {
        return 5;
    }

}
