<?php

/*
 * User Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Services;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Doctrine\ORM\EntityManager;

class UserHelpers {

    private $securityContext;

    private $em;
    private $conn;

    public function __construct(SecurityContext $securityContext, EntityManager $em) {
        $this->securityContext = $securityContext;
        $this->em = $em;
        $this->conn = $em->getConnection();
    }

    /**
     * Get the user role
     * @TODO: this is very simple ACL and has to be improved
     * 
     * @return String
     */
    public function getLoggedUserHighestRole() {

        if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $userRole = 'ROLE_SUPER_ADMIN';
        } else if ($this->securityContext->isGranted('ROLE_USER')) {
            $userRole = 'ROLE_USER';
        } else {
            $userRole = '';
        }

        return $userRole;
    }

    /**
     * Get the logged user
     * 
     * @return User
     */
    public function getLoggedUser() {
        // Getting the logged in user
        $user = $this->securityContext->getToken()->getUser();

        return $user;
    }

    /**
     * Get the logged user username
     * 
     * @return String
     */
    public function getLoggedUserUsername() {
        // Getting the logged in user
        $username = $this->securityContext->getToken()->getUser()->getUsername();

        return $username;
    }

    /**
     * Find user by username
     * 
     * @param string $userName
     * 
     * @return User
     */
    public function getUserByUsername($userName) {
        $user = null;

        if (isset($userName)) {

            // Getting user from database        
            $user = $this->em->getRepository('ApplicationSonataUserBundle:User')
                    ->findOneByUsername($userName);
        }

        return $user;
    }

    /**
     * Retrieving the security identity of the currently logged-in user
     * 
     * @return UserSecurityIdentity
     */
    public function getLoggedUserSecurityIdentity() {
        // Getting the logged in user
        $user = $this->securityContext->getToken()->getUser();

        // Retrieve the security identity of the currently logged-in user
        $securityIdentity = UserSecurityIdentity::fromAccount($user);

        return $securityIdentity;
    }

}
