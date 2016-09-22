<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Sonata\ClassificationBundle\DataFixtures\ORM;

use Application\Sonata\ClassificationBundle\Entity\Context;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ClassificationContextFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $context0 = new Context();
        $context0->setId('default');
        $context0->setName('default');
        $context0->setEnabled(1);
        $context0->setCreatedAt(new \DateTime());
        $context0->setUpdatedAt(new \DateTime());
        $manager->persist($context0);

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

        $context4 = new Context();
        $context4->setId('user_avatar');
        $context4->setName('user_avatar');
        $context4->setEnabled(1);
        $context4->setCreatedAt(new \DateTime());
        $context4->setUpdatedAt(new \DateTime());
        $manager->persist($context4);

        $context5 = new Context();
        $context5->setId('user_hero');
        $context5->setName('user_hero');
        $context5->setEnabled(1);
        $context5->setCreatedAt(new \DateTime());
        $context5->setUpdatedAt(new \DateTime());
        $manager->persist($context5);

        $manager->flush();

        $this->addReference('context0', $context0);
        $this->addReference('context1', $context1);
        $this->addReference('context2', $context2);
        $this->addReference('context3', $context3);
        $this->addReference('context4', $context4);
        $this->addReference('context5', $context5);
    }

    public function getOrder()
    {
        return 4;
    }
}
