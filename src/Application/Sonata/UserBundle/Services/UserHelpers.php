<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Sonata\UserBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Core\SecurityContext;

class UserHelpers
{
    private $securityContext;

    private $em;
    private $conn;

    public function __construct(SecurityContext $securityContext, EntityManager $em)
    {
        $this->securityContext = $securityContext;
        $this->em = $em;
        $this->conn = $em->getConnection();
    }

    /**
     * Get the Logged User Highest Role.
     * Currently three user roles are user for simple ACL
     * ROLE_SUPER_ADMIN, ROLE_USER, ROLE_ANONYMOUS.
     *
     * @return string
     */
    public function getLoggedUserHighestRole()
    {
        $userRole = 'ROLE_ANONYMOUS';

        if ($this->securityContext && $this->securityContext->getToken()) {
            if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN')) {
                $userRole = 'ROLE_SUPER_ADMIN';
            } elseif ($this->securityContext->isGranted('ROLE_USER')) {
                $userRole = 'ROLE_USER';
            }
        }

        return $userRole;
    }

    /**
     * Get the logged user.
     *
     * @return User
     */
    public function getLoggedUser()
    {
        // Getting the logged in user
        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->securityContext->getToken()->getUser();

        return $user;
    }

    /**
     * Get the logged user username.
     *
     * @return string
     */
    public function getLoggedUserUsername()
    {
        // Getting the logged in user
        $username = $this->securityContext->getToken()->getUser()->getUsername();

        return $username;
    }

    /**
     * Find user by username.
     *
     * @param string $userName
     *
     * @return User
     */
    public function getUserByUsername($userName)
    {
        $user = null;

        // Getting user from database
        if (isset($userName)) {
            $user = $this->em->getRepository('ApplicationSonataUserBundle:User')->findOneByUsername($userName);
        }

        return $user;
    }

    /**
     * Retrieving the security identity of the currently logged-in user.
     *
     * @return UserSecurityIdentity
     */
    public function getLoggedUserSecurityIdentity()
    {
        // Getting the logged in user
        $user = $this->getLoggedUser();

        // Retrieve the security identity of the currently logged-in user
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        return $securityIdentity;
    }
}
