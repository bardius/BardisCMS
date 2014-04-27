<?php

/*
 * Media Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\MediaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\MediaBundle\Entity\Media;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $introImage1 = new Media();
        $introImage1->setName('sample_thumb.jpeg');
        $introImage1->setEnabled(0);
        $introImage1->setProviderName('sonata.media.provider.image');
        $introImage1->setProviderStatus(1);
        $introImage1->setProviderReference('sample_thumb.jpeg');
        $introImage1->setMetadataValue('filename', 'sample_thumb.jpeg');
        $introImage1->setWidth(622);
        $introImage1->setHeight(415);
		$introImage1->setContentType('image/jpeg');
		$introImage1->setSize(8043);
        $introImage1->setContext('intro');
        $introImage1->setCreatedAt(new \DateTime());
        $introImage1->setUpdatedAt(new \DateTime());
		$manager->persist($introImage1);
		
		
        $introImage2 = new Media();
        $introImage2->setName('sample_thumb.jpeg');
        $introImage2->setEnabled(0);
        $introImage2->setProviderName('sonata.media.provider.image');
        $introImage2->setProviderStatus(1);
        $introImage2->setProviderReference('sample_thumb.jpeg');
        $introImage2->setMetadataValue('filename', 'sample_thumb.jpeg');
        $introImage2->setWidth(622);
        $introImage2->setHeight(415);
		$introImage2->setContentType('image/jpeg');
		$introImage2->setSize(8043);
        $introImage2->setContext('intro');
        $introImage2->setCreatedAt(new \DateTime());
        $introImage2->setUpdatedAt(new \DateTime());
		$manager->persist($introImage2);
		
		
        $introImage3 = new Media();
        $introImage3->setName('sample_thumb.jpeg');
        $introImage3->setEnabled(0);
        $introImage3->setProviderName('sonata.media.provider.image');
        $introImage3->setProviderStatus(1);
        $introImage3->setProviderReference('sample_thumb.jpeg');
        $introImage3->setMetadataValue('filename', 'sample_thumb.jpeg');
        $introImage3->setWidth(622);
        $introImage3->setHeight(415);
		$introImage3->setContentType('image/jpeg');
		$introImage3->setSize(8043);
        $introImage3->setContext('intro');
        $introImage3->setCreatedAt(new \DateTime());
        $introImage3->setUpdatedAt(new \DateTime());
		$manager->persist($introImage3);
		
		
        $introImage4 = new Media();
        $introImage4->setName('sample_thumb.jpeg');
        $introImage4->setEnabled(0);
        $introImage4->setProviderName('sonata.media.provider.image');
        $introImage4->setProviderStatus(1);
        $introImage4->setProviderReference('sample_thumb.jpeg');
        $introImage4->setMetadataValue('filename', 'sample_thumb.jpeg');
        $introImage4->setWidth(622);
        $introImage4->setHeight(415);
		$introImage4->setContentType('image/jpeg');
		$introImage4->setSize(8043);
        $introImage4->setContext('intro');
        $introImage4->setCreatedAt(new \DateTime());
        $introImage4->setUpdatedAt(new \DateTime());
		$manager->persist($introImage4);
		
		
        $introImage5 = new Media();
        $introImage5->setName('sample_thumb.jpeg');
        $introImage5->setEnabled(0);
        $introImage5->setProviderName('sonata.media.provider.image');
        $introImage5->setProviderStatus(1);
        $introImage5->setProviderReference('sample_thumb.jpeg');
        $introImage5->setMetadataValue('filename', 'sample_thumb.jpeg');
        $introImage5->setWidth(622);
        $introImage5->setHeight(415);
		$introImage5->setContentType('image/jpeg');
		$introImage5->setSize(8043);
        $introImage5->setContext('intro');
        $introImage5->setCreatedAt(new \DateTime());
        $introImage5->setUpdatedAt(new \DateTime());
		$manager->persist($introImage5);
		
		
        $introImage6 = new Media();
        $introImage6->setName('sample_thumb.jpeg');
        $introImage6->setEnabled(0);
        $introImage6->setProviderName('sonata.media.provider.image');
        $introImage6->setProviderStatus(1);
        $introImage6->setProviderReference('sample_thumb.jpeg');
        $introImage6->setMetadataValue('filename', 'sample_thumb.jpeg');
        $introImage6->setWidth(622);
        $introImage6->setHeight(415);
		$introImage6->setContentType('image/jpeg');
		$introImage6->setSize(8043);
        $introImage6->setContext('intro');
        $introImage6->setCreatedAt(new \DateTime());
        $introImage6->setUpdatedAt(new \DateTime());
		$manager->persist($introImage6);
		
		
        $introImage7 = new Media();
        $introImage7->setName('sample_thumb.jpeg');
        $introImage7->setEnabled(0);
        $introImage7->setProviderName('sonata.media.provider.image');
        $introImage7->setProviderStatus(1);
        $introImage7->setProviderReference('sample_thumb.jpeg');
        $introImage7->setMetadataValue('filename', 'sample_thumb.jpeg');
        $introImage7->setWidth(622);
        $introImage7->setHeight(415);
		$introImage7->setContentType('image/jpeg');
		$introImage7->setSize(8043);
        $introImage7->setContext('intro');
        $introImage7->setCreatedAt(new \DateTime());
        $introImage7->setUpdatedAt(new \DateTime());
		$manager->persist($introImage7);
		
		
        $introImage8 = new Media();
        $introImage8->setName('sample_thumb.jpeg');
        $introImage8->setEnabled(0);
        $introImage8->setProviderName('sonata.media.provider.image');
        $introImage8->setProviderStatus(1);
        $introImage8->setProviderReference('sample_thumb.jpeg');
        $introImage8->setMetadataValue('filename', 'sample_thumb.jpeg');
        $introImage8->setWidth(622);
        $introImage8->setHeight(415);
		$introImage8->setContentType('image/jpeg');
		$introImage8->setSize(8043);
        $introImage8->setContext('intro');
        $introImage8->setCreatedAt(new \DateTime());
        $introImage8->setUpdatedAt(new \DateTime());
		$manager->persist($introImage8);
		
		
        $homeBanner1 = new Media();
        $homeBanner1->setName('sample_thumb.jpeg');
        $homeBanner1->setEnabled(0);
        $homeBanner1->setProviderName('sonata.media.provider.image');
        $homeBanner1->setProviderStatus(1);
        $homeBanner1->setProviderReference('sample_thumb.jpeg');
        $homeBanner1->setMetadataValue('filename', 'sample_thumb.jpeg');
        $homeBanner1->setWidth(622);
        $homeBanner1->setHeight(415);
		$homeBanner1->setContentType('image/jpeg');
		$homeBanner1->setSize(8043);
        $homeBanner1->setContext('bgimage');
        $homeBanner1->setCreatedAt(new \DateTime());
        $homeBanner1->setUpdatedAt(new \DateTime());
		$manager->persist($homeBanner1);
		
		
        $homeBanner2 = new Media();
        $homeBanner2->setName('sample_thumb.jpeg');
        $homeBanner2->setEnabled(0);
        $homeBanner2->setProviderName('sonata.media.provider.image');
        $homeBanner2->setProviderStatus(1);
        $homeBanner2->setProviderReference('sample_thumb.jpeg');
        $homeBanner2->setMetadataValue('filename', 'sample_thumb.jpeg');
        $homeBanner2->setWidth(622);
        $homeBanner2->setHeight(415);
		$homeBanner2->setContentType('image/jpeg');
		$homeBanner2->setSize(8043);
        $homeBanner2->setContext('bgimage');
        $homeBanner2->setCreatedAt(new \DateTime());
        $homeBanner2->setUpdatedAt(new \DateTime());
		$manager->persist($homeBanner2);
		
        $manager->flush();
		
		$this->addReference('introImage1', $introImage1);
		$this->addReference('introImage2', $introImage2);
		$this->addReference('introImage3', $introImage3);
		$this->addReference('introImage4', $introImage4);
		$this->addReference('introImage5', $introImage5);
		$this->addReference('introImage6', $introImage6);
		$this->addReference('introImage7', $introImage7);
		$this->addReference('introImage8', $introImage8);
		$this->addReference('homeBanner1', $homeBanner1);
		$this->addReference('homeBanner2', $homeBanner2);
    }
	
	public function getOrder()
    {
        return 3;
    }

}