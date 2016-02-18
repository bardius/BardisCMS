<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;

/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends Controller
{
    // Adding variables required for the rendering of pages
    protected $container;
    private $alias;
    private $id;
    private $extraParams;
    private $currentpage;
    private $totalpageitems;
    private $linkUrlParams;
    private $page;
    private $publishStates;
    private $userName;
    private $settings;
    private $serveMobile;
    private $userRole;
    private $enableHTTPCache;
    private $logged_username;

    // Override the ContainerAware setContainer to accommodate the extra variables
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;

        // Setting the scoped variables required for the rendering of the page
        $this->alias = null;
        $this->id = null;
        $this->extraParams = null;
        $this->currentpage = null;
        $this->totalpageitems = null;
        $this->linkUrlParams = null;
        $this->page = null;
        $this->userName = null;
        $this->logged_username = null;

        // Get the settings from setting bundle
        $this->settings = $this->get('bardiscms_settings.load_settings')->loadSettings();

        // Get the highest user role security permission
        $this->userRole = $this->get('sonata_user.services.helpers')->getLoggedUserHighestRole();

        // Check if mobile content should be served
        $this->serveMobile = $this->get('bardiscms_mobile_detect.device_detection')->testMobile();

        // Set the flag for allowing HTTP cache
        $this->enableHTTPCache = $this->container->getParameter('kernel.environment') == 'prod' && $this->settings->getActivateHttpCache();

        // Set the publish status that is available for the user
        // Very basic ACL permission check
        if ($this->userRole == "") {
            $this->publishStates = array(1);
        } else {
            $this->publishStates = array(1, 2);
        }

        // Get the logged user if any
        $logged_user = $this->get('sonata_user.services.helpers')->getLoggedUser();
        if (is_object($logged_user) && $logged_user instanceof UserInterface) {
            $this->logged_username = $logged_user->getUsername();
        }
    }

    public function registerAction()
    {
        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("register");

        if (!$page) {
            return $this->render404Page();
        }

        $this->page = $page;
        $this->id = $this->page->getId();

        // Simple publishing ACL based on publish state and user role
        if ($this->page->getPublishState() == 0) {
            return $this->render404Page();
        }

        if ($this->page->getPublishState() == 2 && $this->userRole == "") {
            return $this->render404Page();
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                // Original route of FOS Bundle
                $route = 'fos_user_registration_check_email';
                //$route = 'PageBundle_index';
            } else {
                $authUser = true;
                // Original route of FOS Bundle
                $route = 'fos_user_registration_confirmed';
                //$route = 'PageBundle_index';
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        $pageData = array(
            'form' => $form->createView(),
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->logged_username
        );

        // Render register page
        $response = $this->render('FOSUserBundle:Registration:register.html.twig', $pageData);
        // $response = $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), $pageData);

        return $response;
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction()
    {
        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("register");

        if (!$page) {
            return $this->render404Page();
        }

        $this->page = $page;
        $this->id = $this->page->getId();

        // Simple publishing ACL based on publish state and user role
        if ($this->page->getPublishState() == 0) {
            return $this->render404Page();
        }

        if ($this->page->getPublishState() == 2 && $this->userRole == "") {
            return $this->render404Page();
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $email = $this->container->get('session')->get('fos_user_send_confirmation_email/email');
        $this->container->get('session')->remove('fos_user_send_confirmation_email/email');
        $user = $this->container->get('fos_user.user_manager')->findUserByEmail($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        $pageData = array(
            'user' => $user,
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->logged_username
        );

        // Render register page
        $response = $this->render('FOSUserBundle:Registration:checkEmail.html.twig', $pageData);
        // $response = $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:checkEmail.html.'.$this->getEngine(), $pageData);

        return $response;
    }

    /**
     * Receive the confirmation token from user email provider, login the user
     */
    public function confirmAction($token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setConfirmed(true);
        $user->setLastLogin(new \DateTime());

        $this->container->get('fos_user.user_manager')->updateUser($user);
        $response = new RedirectResponse($this->container->get('router')->generate('fos_user_registration_confirmed'));
        $this->authenticateUser($user, $response);

        return $response;
    }

    /**
     * Tell the user his account is now confirmed
     */
    public function confirmedAction()
    {
        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("register/confirmed");

        if (!$page) {
            return $this->render404Page();
        }

        $this->page = $page;
        $this->id = $this->page->getId();

        // Simple publishing ACL based on publish state and user role
        if ($this->page->getPublishState() == 0) {
            return $this->render404Page();
        }

        if ($this->page->getPublishState() == 2 && $this->userRole == "") {
            return $this->render404Page();
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $pageData = array(
            'user' => $user,
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->logged_username
        );

        // Render register page
        $response = $this->render('FOSUserBundle:Registration:confirmed.html.twig', $pageData);
        // $response = $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:confirmed.html.'.$this->getEngine(), $pageData);

        return $response;
    }

    /**
     * Authenticate a user with Symfony Security
     *
     * @param \FOS\UserBundle\Model\UserInterface        $user
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    protected function authenticateUser(UserInterface $user, Response $response)
    {
        try {
            $this->container->get('fos_user.security.login_manager')->loginUser(
                $this->container->getParameter('fos_user.firewall_name'),
                $user,
                $response
            );
        } catch (AccountStatusException $ex) {
            // We simply do not authenticate users which do not pass the user
            // checker (not enabled, expired, etc.).
        }
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }

    protected function getEngine()
    {
        return $this->container->getParameter('fos_user.template.engine');
    }

    // Render the 404 error page
    protected function render404Page() {

        // Get the page with alias 404
        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias('404');

        // Check if page exists
        if (!$this->page) {
            throw $this->createNotFoundException('No 404 error page exists. No page found for with alias 404. Page has id: ' . $this->page->getId());
        }

        // Set the website settings and metatags
        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page))->setStatusCode(404);

        // Set the flag for allowing HTTP cache
        $this->enableHTTPCache = $this->container->getParameter('kernel.environment') == 'prod' && $this->settings->getActivateHttpCache();

        if ($this->enableHTTPCache) {
            $response = $this->setResponseCacheHeaders($response);
        }

        return $response;
    }

    // Set a custom Cache-Control directives
    protected function setResponseCacheHeaders(Response $response) {

        $response->setPublic();
        $response->setLastModified($this->page->getDateLastModified());
        $response->setVary(array('Accept-Encoding', 'User-Agent'));
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setSharedMaxAge(3600);

        return $response;
    }
}
