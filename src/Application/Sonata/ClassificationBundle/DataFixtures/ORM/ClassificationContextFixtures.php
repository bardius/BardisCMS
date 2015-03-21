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
use Application\Sonata\ClassificationBundle\Entity\Context;

class ClassificationContextFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $context4 = new Context();
        $context4->setId('default');
        $context4->setName('default');
        $context4->setEnabled(1);
        $context4->setCreatedAt(new \DateTime());
        $context4->setUpdatedAt(new \DateTime());
        $manager->persist($context4);

        $context1 = new Context();
        $context1->setId('intro');
        $context1->setName('intro');
        $context1->setEnabled(1);
        $context1->setCreatedAt(new \DateTime());
        $context1->setUpdatedAt(new \DateTime());
        $manager->persist($context1);

        $context2 = new Context();
        $context2->setId('bgimage');
        $context2->setName('bgimage');
        $context2->setEnabled(1);
        $context2->setCreatedAt(new \DateTime());
        $context2->setUpdatedAt(new \DateTime());
        $manager->persist($context2);

        $context3 = new Context();
        $context3->setId('icons');
        $context3->setName('icons');
        $context3->setEnabled(1);
        $context3->setCreatedAt(new \DateTime());
        $context3->setUpdatedAt(new \DateTime());
        $manager->persist($context3);

        $manager->flush();

        $this->addReference('context4', $context4);
        $this->addReference('context1', $context1);
        $this->addReference('context2', $context2);
        $this->addReference('context3', $context3);
    }

    public function getOrder() {
        return 4;
    }

}
