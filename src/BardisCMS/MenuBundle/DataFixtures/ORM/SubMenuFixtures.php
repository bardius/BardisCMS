<?php

/*
 * Menu Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\MenuBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BardisCMS\MenuBundle\Entity\Menu;

class SubMenuFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        $menuProfileEditPage = new Menu();
        $menuProfileEditPage->setPage($manager->merge($this->getReference('pageuser_edit_profile')));
        $menuProfileEditPage->setTitle('Manage Profile');
        $menuProfileEditPage->setMenuType(Menu::TYPE_PAGE);
        $menuProfileEditPage->setRoute(Menu::ROUTE_SHOWPAGE);
        $menuProfileEditPage->setAccessLevel(Menu::STATUS_AUTHONLY);
        $menuProfileEditPage->setParent($manager->merge($this->getReference('menuProfilePage'))->getId());
        $menuProfileEditPage->setMenuGroup(Menu::GROUP_MAIN);
        $menuProfileEditPage->setPublishState(Menu::STATE_PUBLISHED);
        $menuProfileEditPage->setOrdering(0);
        $manager->persist($menuProfileEditPage);

        $menuLogoutPage = new Menu();
        $menuLogoutPage->setTitle('Logout');
        $menuLogoutPage->setMenuType(Menu::TYPE_INTERNAL_URL);
        $menuLogoutPage->setRoute(Menu::ROUTE_NONE);
        $menuLogoutPage->setExternalUrl('/logout');
        $menuLogoutPage->setAccessLevel(Menu::STATUS_AUTHONLY);
        $menuLogoutPage->setParent($manager->merge($this->getReference('menuProfilePage'))->getId());
        $menuLogoutPage->setMenuGroup(Menu::GROUP_MAIN);
        $menuLogoutPage->setPublishState(Menu::STATE_PUBLISHED);
        $menuLogoutPage->setOrdering(1);
        $manager->persist($menuLogoutPage);

        $manager->flush();

        $this->addReference('menuProfileEditPage', $menuProfileEditPage);
        $this->addReference('menuLogoutPage', $menuLogoutPage);
    }

    public function getOrder() {
        return 17;
    }

}
