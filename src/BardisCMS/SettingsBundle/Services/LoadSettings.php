<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\SettingsBundle\Services;

use Doctrine\ORM\EntityManager;

class LoadSettings
{
    private $em;
    private $conn;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->conn = $em->getConnection();
    }

    public function loadSettings()
    {
        //$settings = $this->em->getRepository('SettingsBundle:Settings')->findAll();
        $settings = $this->em->getRepository('SettingsBundle:Settings')->findOneByActivateSettings(true);

        if (empty($settings)) {
            return null;
        }

        return $settings;
    }
}
