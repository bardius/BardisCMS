<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Sonata\UserBundle\EventListener;

use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

/**
 * Collect failed authentication attempts to prevent brute force attacks to authentication
 *
 * References :
 *   Collect Failed Authentication Attempts : http://php-and-symfony.matthiasnoback.nl/2013/03/symfony2-security-enhancements-part-ii/
 */
class AuthenticationListener implements EventSubscriberInterface
{
    protected $userManager;
    private $container;

    public function __construct(ContainerInterface $container, UserManagerInterface $userManager) {
        $this->container = $container;
        $this->userManager = $userManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        );
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $username = $token->getUsername();
        $user = $this->container->get('sonata_user.services.helpers')->getUserByUsername($username);

        if(!$user) {
            return;
        }

        if($user->getFailedAttempts() < 4 && $user->isLocked() === false){
            $user->setFailedAttempts($user->getFailedAttempts() + 1);
            $this->persistUser($user);
        }
        else {
            $user->setLocked(true);
            $this->persistUser($user);
        }
    }

    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $username = $token->getUsername();
        $user = $this->container->get('sonata_user.services.helpers')->getUserByUsername($username);

        if(!$user) {
            return;
        }

        if($user->getFailedAttempts() > 0 && $user->isLocked() === false){
            $user->setFailedAttempts(0);
            $this->persistUser($user);
        }
    }


    /**
     * persistUser after processing the form for AccountPreferencesForm.
     *
     * @param UserInterface $user
     */
    protected function persistUser(UserInterface $user)
    {
        $this->userManager->updateUser($user);
    }
}
