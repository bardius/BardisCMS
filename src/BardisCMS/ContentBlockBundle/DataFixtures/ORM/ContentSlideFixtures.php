<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\ContentBlockBundle\DataFixtures\ORM;

use BardisCMS\ContentBlockBundle\Entity\ContentSlide;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ContentSlideFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $homeSlide1 = new ContentSlide();
        $homeSlide1->setImageLinkTitle('Slide 1');
        $homeSlide1->setImageLinkURL('/blog/events');
        $homeSlide1->setImagefile($manager->merge($this->getReference('homeBanner1')));
        $manager->persist($homeSlide1);

        $homeSlide2 = new ContentSlide();
        $homeSlide2->setImageLinkTitle('Slide 2');
        $homeSlide2->setImagefile($manager->merge($this->getReference('homeBanner2')));
        $manager->persist($homeSlide2);

        $manager->flush();

        $this->addReference('homeSlide1', $homeSlide1);
        $this->addReference('homeSlide2', $homeSlide2);
    }

    public function getOrder()
    {
        return 7;
    }
}
