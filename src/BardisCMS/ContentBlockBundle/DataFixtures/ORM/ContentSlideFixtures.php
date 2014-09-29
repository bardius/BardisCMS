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
use BardisCMS\ContentBlockBundle\Entity\ContentSlide;

class ContentSlideFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

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

    public function getOrder() {
        return 4;
    }

}
