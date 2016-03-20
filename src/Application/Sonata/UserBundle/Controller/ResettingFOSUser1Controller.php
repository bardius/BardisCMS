<?php

/*
 * Sonata User Bundle Overrides
 * This file is part of the BardisCMS.
 * Manage the extended Sonata User entity with extra information for the users
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;

use BardisCMS\PageBundle\Entity\Page as Page;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller managing the resetting of the password
 */
class ResettingFOSUser1Controller extends Controller
{
    // Adding variables required for the rendering of pages
    protected $container;
    private $page;
    private $publishStates;
    private $userName;
    private $settings;
    private $serveMobile;
    private $userRole;
    private $enableHTTPCache;
    private $isAjaxRequest;

    const RESET_REQUEST_PAGE_ALIAS = 'resetting/request';
    const RESET_SEND_EMAIL_ALIAS = 'resetting/send-email';
    const RESET_CHECK_EMAIL_PAGE_ALIAS = "resetting/check-email";
    const RESET_PASSWORD_PAGE_ALIAS = "resetting/reset";

    const SESSION_EMAIL = 'fos_user_send_resetting_email/email';

    /**
     * Override the ContainerAware setContainer to accommodate the extra variables
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;

        // Setting the scoped variables required for the rendering of the page
        $this->page = null;
        $this->userName = null;

        // Get the settings from setting bundle
        $this->settings = $this->get('bardiscms_settings.load_settings')->loadSettings();

        // Get the highest user role security permission
        $this->userRole = $this->get('sonata_user.services.helpers')->getLoggedUserHighestRole();

        // Check if mobile content should be served
        $this->serveMobile = $this->get('bardiscms_mobile_detect.device_detection')->testMobile();

        // Set the flag for allowing HTTP cache
        $this->enableHTTPCache = $this->container->getParameter('kernel.environment') == 'prod' && $this->settings->getActivateHttpCache();

        // Check if request was Ajax based
        $this->isAjaxRequest = $this->get('bardiscms_page.services.ajax_detection')->isAjaxRequest();

        // Set the publish statuses that are available for the user
        $this->publishStates = $this->get('bardiscms_page.services.helpers')->getAllowedPublishStates($this->userRole);

        // Get the logged user if any
        $logged_user = $this->get('sonata_user.services.helpers')->getLoggedUser();
        if (is_object($logged_user) && $logged_user instanceof UserInterface) {
            $this->userName = $logged_user->getUsername();
        }
    }

    /**
     * Rendering of the Request user password reset page
     *
     * @return Response
     */
    public function requestAction()
    {
        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($this::RESET_REQUEST_PAGE_ALIAS);

        if (!$this->page) {
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }

        // Simple publishing ACL based on publish state and user Allowed Publish States
        $accessAllowedForUserRole = $this->get('bardiscms_page.services.helpers')->isUserAccessAllowedByRole(
            $this->page->getPublishState(),
            $this->publishStates
        );

        if(!$accessAllowedForUserRole){
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_401);
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $pageData = array(
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->userName
        );

        // Render Request user password reset page
        $response = $this->render('FOSUserBundle:Resetting:request.html.twig', $pageData);

        return $response;
    }

    /**
     * Submit the Request user password reset form and send email
     * before redirecting to the next user journey page
     *
     * @return RedirectResponse
     */
    public function sendEmailAction()
    {
        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($this::RESET_SEND_EMAIL_ALIAS);

        if (!$this->page) {
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }

        // Simple publishing ACL based on publish state and user Allowed Publish States
        $accessAllowedForUserRole = $this->get('bardiscms_page.services.helpers')->isUserAccessAllowedByRole(
            $this->page->getPublishState(),
            $this->publishStates
        );

        if(!$accessAllowedForUserRole){
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_401);
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);
        $username = $this->container->get('request')->request->get('username');
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        // If user does not exist
        if (null === $user) {
            // If the request was Ajax based
            if($this->isAjaxRequest){
                if($username){
                    return $this->onAjaxError($this->container->get('translator')->trans('resetting.request.invalid_username', array('%username%' => $username), 'SonataUserBundle'));
                }
                else {
                    return $this->onAjaxError($this->container->get('translator')->trans('resetting.request.empty_username', array(), 'SonataUserBundle'));
                }
            }

            $pageData = array(
                'invalid_username' => $username,
                'page' => $this->page,
                'mobile' => $this->serveMobile,
                'logged_username' => $this->userName
            );

            // Render reset request page
            $response = $this->render('FOSUserBundle:Resetting:request.html.twig', $pageData);

            return $response;
        }

        // If user password request token has not been expired
        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            // If the request was Ajax based
            if($this->isAjaxRequest){
                return $this->onAjaxError($this->container->get('translator')->trans('resetting.password_already_requested', array(), 'SonataUserBundle'));
            }

            $pageData = array(
                'page' => $this->page,
                'mobile' => $this->serveMobile,
                'logged_username' => $this->userName
            );

            // Render passwordAlreadyRequested page
            $response = $this->render('FOSUserBundle:Resetting:passwordAlreadyRequested.html.twig', $pageData);

            return $response;
        }

        if (null === $user->getConfirmationToken()) {
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->container->get('session')->set(static::SESSION_EMAIL, $user->getEmail() );
        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);

        // Disable user until password reset process is completed
        $user->setEnabled(false);
        $user->setPasswordRequestedAt(new \DateTime());

        $this->container->get('fos_user.user_manager')->updateUser($user);
        $responseURL = $this->container->get('router')->generate('sonata_user_resetting_check_email');

        // If the request was Ajax based
        if($this->isAjaxRequest){
            return $this->onAjaxSuccess($responseURL);
        }

        return new RedirectResponse($responseURL);
    }

    /**
     * Tell the user to check his email provider after successful password reset
     *
     * @return Response
     */
    public function checkEmailAction()
    {
        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($this::RESET_CHECK_EMAIL_PAGE_ALIAS);

        if (!$this->page) {
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }

        // Simple publishing ACL based on publish state and user Allowed Publish States
        $accessAllowedForUserRole = $this->get('bardiscms_page.services.helpers')->isUserAccessAllowedByRole(
            $this->page->getPublishState(),
            $this->publishStates
        );

        if(!$accessAllowedForUserRole){
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_401);
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $session = $this->container->get('session');
        $email = $session->get(static::SESSION_EMAIL);
        $session->remove(static::SESSION_EMAIL);

        // If the user does not come from the sendEmail action
        if (empty($email)) {
            return new RedirectResponse($this->container->get('router')->generate('sonata_user_resetting_request'));
        }

        $pageData = array(
            'email' => $email,
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->userName
        );

        // Render check email page
        $response = $this->render('FOSUserBundle:Resetting:checkEmail.html.twig', $pageData);

        return $response;
    }

    /**
     * Password Reset with success user password page
     *
     * @return Response
     */
    public function resetAction($token)
    {
        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($this::RESET_PASSWORD_PAGE_ALIAS);
        $redirectToRouteNameOnSuccess = 'sonata_user_profile_show';

        if (!$this->page) {
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }

        // Simple publishing ACL based on publish state and user Allowed Publish States
        $accessAllowedForUserRole = $this->get('bardiscms_page.services.helpers')->isUserAccessAllowedByRole(
            $this->page->getPublishState(),
            $this->publishStates
        );

        if(!$accessAllowedForUserRole){
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_401);
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            // If the request was Ajax based
            if($this->isAjaxRequest){
                return $this->onAjaxError($this->container->get('translator')->trans('resetting.reset.invalid_user', array('token' => $token), 'SonataUserBundle'));
            }
            else {
                return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_401);
                //throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
            }
        }

        if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            // If the request was Ajax based
            if($this->isAjaxRequest){
                return $this->onAjaxError($this->container->get('translator')->trans('resetting.reset.token_expired', array('token' => $token), 'SonataUserBundle'));
            }
            else {
                return new RedirectResponse($this->container->get('router')->generate('sonata_user_resetting_request'));
            }
        }

        $form = $this->container->get('sonata_user.resetting.form');
        $formHandler = $this->container->get('sonata_user.resetting.form.handler');

        $process = $formHandler->process($user);

        if ($process) {
            $this->setFlash('sonata_user_success', 'resetting.flash.success');
            $responseURL = $this->container->get('router')->generate($redirectToRouteNameOnSuccess);
            $response = new RedirectResponse($responseURL);
            // Enable user again after successful password reset journey
            $user->setEnabled(true);
            $this->authenticateUser($user, $response);

            // If the request was Ajax based
            if($this->isAjaxRequest){
                return $this->onAjaxSuccess($responseURL);
            }

            return $response;
        }

        if(!$process && $this->isAjaxRequest){
            return $this->onAjaxFieldsError($formHandler);
        }

        $pageData = array(
            'token' => $token,
            'form' => $form->createView(),
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->userName
        );

        // Render Password Reset with success user password page
        $response = $this->render('FOSUserBundle:Resetting:reset.html.twig', $pageData);

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
        return $this->container->get('router')->generate('sonata_user_profile_show');
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

    /**
     * Extend with new method to handle Ajax response with errors
     *
     * @param $formHandler
     *
     * @return Response
     */
    protected function onAjaxFieldsError($formHandler)
    {
        $errorList = $formHandler->getErrors();
        $formMessage = 'resetting.reset.error';
        $formHasErrors = true;

        $ajaxFormData = array(
            'errors' => $errorList,
            'formMessage' => $this->container->get('translator')->trans($formMessage, array(), 'SonataUserBundle'),
            'hasErrors' => $formHasErrors
        );

        $ajaxFormResponse = new Response(json_encode($ajaxFormData));
        $ajaxFormResponse->headers->set('Content-Type', 'application/json');

        return $ajaxFormResponse;
    }

    /**
     * Extend with new method to handle Ajax response with errors
     *
     * @param $formMessage
     *
     * @return Response
     */
    protected function onAjaxError($formMessage)
    {
        $errorList = array();
        $formHasErrors = true;

        $ajaxFormData = array(
            'errors' => $errorList,
            'formMessage' => $formMessage,
            'hasErrors' => $formHasErrors
        );

        $ajaxFormResponse = new Response(json_encode($ajaxFormData));
        $ajaxFormResponse->headers->set('Content-Type', 'application/json');

        return $ajaxFormResponse;
    }

    /**
     * Extend with new method to handle Ajax response with success
     *
     * @param String $redirectURL
     *
     * @return Response
     */
    protected function onAjaxSuccess($redirectURL)
    {
        $errorList = array();
        $formMessage = '';
        $formHasErrors = false;

        $ajaxFormData = array(
            'errors' => $errorList,
            'formMessage' => $formMessage,
            'hasErrors' => $formHasErrors,
            'redirectURL' => $redirectURL
        );

        $ajaxFormResponse = new Response(json_encode($ajaxFormData));
        $ajaxFormResponse->headers->set('Content-Type', 'application/json');

        return $ajaxFormResponse;
    }
}
