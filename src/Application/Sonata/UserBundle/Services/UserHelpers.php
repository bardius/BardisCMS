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

class UserHelpers {    

	private $securityContext;

	public function __construct(SecurityContext $securityContext) {
		$this->securityContext = $securityContext;
	}
    
    // Get the user role ( @TODO: this is very simple ACL and has to be improved )
    public function getLoggedUserHighestRole()
    {
        
        if ($this->securityContext->isGranted('ROLE_SUPER_ADMIN')) {
            $userRole = 'ROLE_SUPER_ADMIN';
        }
        else if ($this->securityContext->isGranted('ROLE_USER')) {
            $userRole = 'ROLE_USER';
        }
        else
        {
            $userRole = '';
        }
        
        return $userRole;
    } 

}