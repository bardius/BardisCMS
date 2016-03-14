<?php

/*
 * Sonata User Bundle Overrides
 * This file is part of the BardisCMS.
 * Manage the extended Sonata User entity with extra information for the users
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\UserBundle\Entity\User;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface {

    /*
     * The sample Fixtures for the Sonata User bundle
     * A SuperAdmin user and a test user are created
     *
     */
    public function load(ObjectManager $manager) {
        $admin = new User();
        $admin->setCreatedAt(new \DateTime());
        $admin->setUpdatedAt(new \DateTime());
        $admin->setUsername('administrator');
        $admin->setUsernameCanonical('administrator');
        $admin->setEmail('admin@domain.com');
        $admin->setEmailCanonical('admin@domain.com');
        $admin->setEnabled(1);
        $admin->setPlainPassword('Admin1');
        $admin->setConfirmed(true);
        $admin->setTermsAccepted(true);
        $admin->setSuperAdmin(true);
        //$admin->addRole(static::ROLE_SUPER_ADMIN);
        $manager->persist($admin);

        $test01 = new User();
        $test01->setCreatedAt(new \DateTime());
        $test01->setUpdatedAt(new \DateTime());
        $test01->setUsername('test01');
        $test01->setUsernameCanonical('test01');
        $test01->setEmail('test01@domain.com');
        $test01->setEmailCanonical('test01@domain.com');
        $test01->setEnabled(1);
        $test01->setPlainPassword('Test1234');
        $test01->setConfirmed(true);
        $test01->setTermsAccepted(true);
        $test01->setSuperAdmin(false);
        $test01->addRole(static::ROLE_USER);
        $manager->persist($test01);

        $manager->flush();

        $this->addReference('admin', $admin);
        $this->addReference('test01', $test01);
    }

    public function getOrder() {
        return 0;
    }

}
