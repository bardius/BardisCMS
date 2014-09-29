<?php

/*
 * Tag Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\TagBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BardisCMS\TagBundle\Entity\Tag;

class TagFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $tagSample1 = new Tag();
        $tagSample1->setTitle('Sample Tag 1');
        $manager->persist($tagSample1);

        $tagSample2 = new Tag();
        $tagSample2->setTitle('Sample Tag 2');
        $tagSample2->setTagCategory('blog');
        $manager->persist($tagSample2);

        $manager->flush();

        $this->addReference('tagSample1', $tagSample1);
        $this->addReference('tagSample2', $tagSample2);
    }

    public function getOrder() {
        return 3;
    }

}