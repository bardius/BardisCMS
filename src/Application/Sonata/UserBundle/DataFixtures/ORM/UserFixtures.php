<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    /*
     * The sample Fixtures for the Sonata User bundle
     * A SuperAdmin user and a test user are created
     *
     */
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setCreatedAt(new \DateTime());
        $admin->setUpdatedAt(new \DateTime());
        $admin->setUsername('administrator');
        $admin->setUsernameCanonical('administrator');
        $admin->setEmail('admin@domain.com');
        $admin->setEmailCanonical('admin@domain.com');
        $admin->setEnabled(1);
        $admin->setIsSystemUser(1);
        $admin->setPlainPassword('Admin1234');
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
        $test01->setIsSystemUser(0);
        $test01->setPlainPassword('Test1234');
        $test01->setConfirmed(true);
        $test01->setTermsAccepted(true);
        $test01->setSuperAdmin(false);
        //$test01->addRole(static::ROLE_USER);
        $manager->persist($test01);

        $test02 = new User();
        $test02->setCreatedAt(new \DateTime());
        $test02->setUpdatedAt(new \DateTime());
        $test02->setUsername('test02');
        $test02->setUsernameCanonical('test02');
        $test02->setEmail('test02@domain.com');
        $test02->setEmailCanonical('test02@domain.com');
        $test02->setEnabled(1);
        $test02->setIsSystemUser(0);
        $test02->setPlainPassword('Test1234');
        $test02->setConfirmed(true);
        $test02->setTermsAccepted(true);
        $test02->setSuperAdmin(false);
        //$test02->addRole(static::ROLE_USER);
        $manager->persist($test02);

        $test03 = new User();
        $test03->setCreatedAt(new \DateTime());
        $test03->setUpdatedAt(new \DateTime());
        $test03->setUsername('test03');
        $test03->setUsernameCanonical('test03');
        $test03->setEmail('test03@domain.com');
        $test03->setEmailCanonical('test03@domain.com');
        $test03->setEnabled(1);
        $test03->setIsSystemUser(0);
        $test03->setPlainPassword('Test1234');
        $test03->setConfirmed(true);
        $test03->setTermsAccepted(true);
        $test03->setSuperAdmin(false);
        //$test03->addRole(static::ROLE_USER);
        $manager->persist($test03);

        $manager->flush();

        $this->addReference('admin', $admin);
        $this->addReference('test01', $test01);
        $this->addReference('test02', $test02);
        $this->addReference('test03', $test03);
    }

    public function getOrder()
    {
        return 0;
    }
}
