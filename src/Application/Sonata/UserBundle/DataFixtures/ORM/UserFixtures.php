<?php

/*
 * User Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\UserBundle\Entity\User;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setCreatedAt(new \DateTime());
        $admin->setUpdatedAt(new \DateTime());
        $admin->setUsername('administrator');
        $admin->setUsernameCanonical('administrator');
        $admin->setEmail('george@bardis.info');
        $admin->setEmailCanonical('george@bardis.info');
        $admin->setEnabled(1);
        $admin->setPlainPassword('!p@$$w0rd!');
		$admin->setSuperAdmin(true);
		//$admin->addRole(static::ROLE_SUPER_ADMIN);
		$manager->persist($admin);
		
        $manager->flush();
		
		$this->addReference('admin', $admin);
    }
	
	public function getOrder()
    {
        return 0;
    }

}