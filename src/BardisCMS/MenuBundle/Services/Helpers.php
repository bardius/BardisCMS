<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\MenuBundle\Services;

use BardisCMS\MenuBundle\Entity\Menu as Menu;

class Helpers {

    public function __construct() {
    }

    // Get the AccessLevels that are allowed for the user (in sync with page access levels)
    public function getAllowedAccessLevels($userHighestRole) {

        $allowedAccessLevels = array();

        // Setting ROLE_ANONYMOUS role for brevity
        if ($userHighestRole == "") {
            $userHighestRole = "ROLE_ANONYMOUS";
        }

        // Very basic ACL permission check
        switch ($userHighestRole) {
            case "ROLE_ANONYMOUS":
                array_push(
                    $allowedAccessLevels,
                    Menu::STATUS_PUBLIC,
                    Menu::STATUS_NONAUTHONLY
                );
                break;
            case "ROLE_USER":
                array_push(
                    $allowedAccessLevels,
                    Menu::STATUS_PUBLIC,
                    Menu::STATUS_AUTHONLY
                );
                break;
            case "ROLE_SUPER_ADMIN":
                array_push(
                    $allowedAccessLevels,
                    Menu::STATUS_PUBLIC,
                    Menu::STATUS_ADMINONLY,
                    Menu::STATUS_AUTHONLY
                );
                break;
            default:
                array_push(
                    $allowedAccessLevels,
                    Menu::STATUS_PUBLIC,
                    Menu::STATUS_NONAUTHONLY
                );
        }

        return $allowedAccessLevels;
    }

    // Simple publishing ACL based on publish state and user Allowed Publish States
    public function isUserAccessAllowedByRole($accessLevel, $userAccessLevels) {

        $accessAllowedForUserRole = false;

        if(in_array($accessLevel, $userAccessLevels)){
            $accessAllowedForUserRole = true;
        }

        return $accessAllowedForUserRole;
    }
}
