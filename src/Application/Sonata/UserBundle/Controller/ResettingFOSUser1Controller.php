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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller managing the resetting of the password
 */
class ResettingFOSUser1Controller extends Controller
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
    const SESSION_EMAIL = 'fos_user_send_resetting_email/email';

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

    /**
     * Request reset user password: show form
     */
    public function requestAction()
    {
        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("resetting/request");

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

        $pageData = array(
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->logged_username
        );

        // Render login page
        $response = $this->render('FOSUserBundle:Resetting:request.html.twig', $pageData);
        // $response = $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:request.html.'.$this->getEngine(), $pageData);

        return $response;
    }

    /**
     * Request reset user password: submit form and send email
     */
    public function sendEmailAction()
    {
        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("resetting/send-email");

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
        $username = $this->container->get('request')->request->get('username');
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        // If user does not exist
        if (null === $user) {
            $pageData = array(
                'invalid_username' => $username,
                'page' => $this->page,
                'mobile' => $this->serveMobile,
                'logged_username' => $this->logged_username
            );

            // Render reset request page
            $response = $this->render('FOSUserBundle:Resetting:request.html.twig', $pageData);
            //$response = $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:request.html.'.$this->getEngine(), $pageData);

            return $response;
        }

        // If user password request token has not been expired
        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            $pageData = array(
                'page' => $this->page,
                'mobile' => $this->serveMobile,
                'logged_username' => $this->logged_username
            );

            // Render passwordAlreadyRequested page
            $response = $this->render('FOSUserBundle:Resetting:passwordAlreadyRequested.html.twig', $pageData);
            //$response = $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:passwordAlreadyRequested.html.'.$this->getEngine(), $pageData);

            return $response;
        }

        if (null === $user->getConfirmationToken()) {
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->container->get('session')->set(static::SESSION_EMAIL, $user->getEmail() );
        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);

        return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_check_email'));
    }

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction()
    {
        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("resetting/check-email");

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

        $session = $this->container->get('session');
        $email = $session->get(static::SESSION_EMAIL);
        $session->remove(static::SESSION_EMAIL);

        // If the user does not come from the sendEmail action
        if (empty($email)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
        }

        $pageData = array(
            'email' => $email,
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->logged_username
        );

        // Render login page
        $response = $this->render('FOSUserBundle:Resetting:checkEmail.html.twig', $pageData);
        // $response = $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:checkEmail.html.'.$this->getEngine(), $pageData);

        return $response;
    }

    /**
     * Reset user password
     */
    public function resetAction($token)
    {
        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("resetting/reset");
        //TODO: redirect to user profile page upon success
        $redirectToRouteNameOnSuccess = 'fos_user_security_login';

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

        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
        }

        $form = $this->container->get('fos_user.resetting.form');
        $formHandler = $this->container->get('fos_user.resetting.form.handler');
        $process = $formHandler->process($user);

        if ($process) {
            $this->setFlash('fos_user_success', 'resetting.flash.success');
            // Original FOS User bundle response
            //$response = new RedirectResponse($this->getRedirectionUrl($user));
            $response = new RedirectResponse($this->container->get('router')->generate($redirectToRouteNameOnSuccess));
            $this->authenticateUser($user, $response);

            return $response;
        }

        $pageData = array(
            'token' => $token,
            'form' => $form->createView(),
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->logged_username
        );

        // Render login page
        $response = $this->render('FOSUserBundle:Resetting:reset.html.twig', $pageData);
        // $response = $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:reset.html.'.$this->getEngine(), $pageData);

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
     * Generate the redirection url when the resetting is completed.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->container->get('router')->generate('fos_user_profile_show');
    }

    /**
     * Get the truncated email displayed when requesting the resetting.
     *
     * The default implementation only keeps the part following @ in the address.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getObfuscatedEmail(UserInterface $user)
    {
        $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = '...' . substr($email, $pos);
        }

        return $email;
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
