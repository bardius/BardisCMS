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

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use BardisCMS\PageBundle\Entity\Page as Page;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller managing the user profile
 */
class ProfileFOSUser1Controller extends Controller
{
    protected $container;
    private $page;
    private $publishStates;
    private $userName;
    private $settings;
    private $serveMobile;
    private $userRole;
    private $enableHTTPCache;
    private $logged_user;
    private $isAjaxRequest;

    const PROFILE_PAGE_ALIAS = "profile";
    const PROFILE_EDIT_PAGE_ALIAS = "edit-profile";

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
        $this->logged_user = $this->get('sonata_user.services.helpers')->getLoggedUser();
        if (is_object($this->logged_user) && $this->logged_user instanceof UserInterface) {
            $this->userName = $this->logged_user->getUsername();
        }
    }

    /**
     * Show the user profile
     *
     * @param String $alias
     * @param String $userName
     *
     * @return Response response
     */
    public function showAction($alias = 'profile', $userName = 'none')
    {
        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($alias);

        if($userName == 'none' && $this->userName === null){
            return $this->redirect($this->generateUrl('sonata_user_profile_list'));
        }
        elseif($userName == 'none'){
            $userName = $this->userName;
        }

        $page_user = $this->container->get('sonata_user.services.helpers')->getUserByUsername($userName);

        if (!$this->page || !$page_user) {
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

        // Get the details of the requested user
        $pageData = array(
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'profile_owner' => $userName === $this->userName ? true : false,
            'page_username' => $userName,
            'logged_username' => $this->userName,
            'page_user' => $page_user,
            'blocks' => $this->container->getParameter('sonata.user.configuration.profile_blocks'),
        );

        // Render profile page
        $response = $this->render('SonataUserBundle:Profile:show.html.twig', $pageData);

        return $response;
    }

    /**
     * Render the authentication details edit page
     * that is not used anymore in BardisCMS
     *
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     * @deprecated
     */
    public function editAuthenticationAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($this::PROFILE_EDIT_PAGE_ALIAS);

        if (!$this->page) {
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $form = $this->get('sonata.user.authentication.form');
        $formHandler = $this->get('sonata.user.authentication.form_handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('sonata_user_success', 'profile.flash.updated');

            return $this->redirectToRoute('sonata_user_profile_show');
        }

        $pageData = array(
            'form' => $form->createView(),
            'user' => $user,
            'page' => $this->page,
            'logged_username' => $this->userName,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->userName
        );

        // Render authentication details edit page
        $response = $this->render('SonataUserBundle:Profile:edit_authentication.html.twig', $pageData);

        return $response;
    }

    /**
     * Edit the user profile & authentication details
     *
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function editProfileAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($this::PROFILE_EDIT_PAGE_ALIAS);

        if (!$this->page) {
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        // Password Change Form
        $passwordForm = $this->container->get('sonata_user.change_password.form');
        $passwordFormHandler = $this->container->get('sonata_user.change_password.form.handler');

        // Generic Details Form
        $genericDetailsForm = $this->container->get('sonata_user.generic_details.form');
        $genericDetailsFormHandler = $this->container->get('sonata_user.generic_details.form.handler');

        // Contact Details Form
        $contactDetailsForm = $this->container->get('sonata_user.contact_details.form');
        $contactDetailsFormHandler = $this->container->get('sonata_user.contact_details.form.handler');

        // Account Preferences Form
        $accountPreferencesForm = $this->container->get('sonata_user.account_preferences.form');
        $accountPreferencesFormHandler = $this->container->get('sonata_user.account_preferences.form.handler');

        // Account Media Form
        $accountMediaForm = $this->container->get('sonata_user.account_media.form');
        $accountMediaFormHandler = $this->container->get('sonata_user.account_media.form.handler');

        // Determine what form to process
        $formSection = $this->container->get('request')->request->get('form_section');

        switch ($formSection) {
            case "generic_details":
                $genericDetailsProcess = $genericDetailsFormHandler->process($user);
                if ($genericDetailsProcess) {
                    $this->addFlash('fos_user_success', 'profile.flash.updated');
                }

                // If the request was Ajax based
                if($this->isAjaxRequest){
                    if ($genericDetailsProcess) {
                        return $this->onAjaxSuccess('profile.flash.updated');
                    } else {
                        return $this->onAjaxError($genericDetailsFormHandler);
                    }
                }
                break;
            case "contact":
                $contactDetailsProcess = $contactDetailsFormHandler->process($user);
                if ($contactDetailsProcess) {
                    $this->addFlash('fos_user_success', 'profile.flash.updated');
                }

                // If the request was Ajax based
                if($this->isAjaxRequest){
                    if ($contactDetailsProcess) {
                        return $this->onAjaxSuccess('profile.flash.updated');
                    } else {
                        return $this->onAjaxError($contactDetailsFormHandler);
                    }
                }
                break;
            case "preferences":
                $accountPreferencesProcess = $accountPreferencesFormHandler->process($user);
                if ($accountPreferencesProcess) {
                    $this->addFlash('fos_user_success', 'profile.flash.updated');
                }

                // If the request was Ajax based
                if($this->isAjaxRequest){
                    if ($accountPreferencesProcess) {
                        return $this->onAjaxSuccess('profile.flash.updated');
                    } else {
                        return $this->onAjaxError($accountPreferencesFormHandler);
                    }
                }
                break;
            case "media":
                $accountMediaProcess = $accountMediaFormHandler->process($user);
                if ($accountMediaProcess) {
                    $this->addFlash('fos_user_success', 'profile.flash.updated');
                }

                // If the request was Ajax based
                if($this->isAjaxRequest){
                    if ($accountMediaProcess) {
                        return $this->onAjaxSuccess('profile.flash.updated');
                    } else {
                        return $this->onAjaxError($accountMediaFormHandler);
                    }
                }
                break;
            case "password":
                $passwordProcess = $passwordFormHandler->process($user);
                if ($passwordProcess) {
                    $this->addFlash('fos_user_success', 'change_password.flash.success');
                }

                // If the request was Ajax based
                if($this->isAjaxRequest){
                    if ($passwordProcess) {
                        return $this->onAjaxSuccess('profile.flash.updated');
                    } else {
                        return $this->onAjaxError($passwordFormHandler);
                    }
                }
                break;
        }

        $pageData = array(
            'passwordForm' => $passwordForm->createView(),
            'genericDetailsForm' => $genericDetailsForm->createView(),
            'contactDetailsForm' => $contactDetailsForm->createView(),
            'accountPreferencesForm' => $accountPreferencesForm->createView(),
            'accountMediaForm' => $accountMediaForm->createView(),
            'page' => $this->page,
            'logged_username' => $this->userName,
            'mobile' => $this->serveMobile
        );

        // Render register page
        $response = $this->render('SonataUserBundle:Profile:edit.html.twig', $pageData);

        return $response;
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->get('session')->getFlashBag()->set($action, $value);
    }

    /**
     * Extend with new method to handle form processing
     *
     * @param $formHandler
     * @param $user
     * @param $flashSuccessMsg
     *
     * @return Response
     */
    protected function processFormSubmission($formHandler, $user, $flashSuccessMsg)
    {
        $formProcess = $formHandler->process($user);
        if ($formProcess) {
            $this->addFlash('fos_user_success', $flashSuccessMsg);
        }

        // If the request was Ajax based
        if($this->isAjaxRequest){
            if ($formProcess) {
                return $this->onAjaxSuccess($flashSuccessMsg);
            } else {
                return $this->onAjaxError($formHandler);
            }
        }
    }

    /**
     * Extend with new method to handle Ajax response with errors
     *
     * @param $formHandler
     *
     * @return Response
     */
    protected function onAjaxError($formHandler)
    {
        $errorList = $formHandler->getErrors();
        $formMessage = 'profile.responses.error';
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
     * Extend with new method to handle Ajax response with success
     *
     * @param String $successMsg
     *
     * @return Response
     */
    protected function onAjaxSuccess($successMsg)
    {
        $errorList = array();
        $formMessage = $successMsg;
        $formHasErrors = false;

        $ajaxFormData = array(
            'errors' => $errorList,
            'formMessage' => $this->container->get('translator')->trans($formMessage, array(), 'SonataUserBundle'),
            'hasErrors' => $formHasErrors
        );

        $ajaxFormResponse = new Response(json_encode($ajaxFormData));
        $ajaxFormResponse->headers->set('Content-Type', 'application/json');

        return $ajaxFormResponse;
    }
}
