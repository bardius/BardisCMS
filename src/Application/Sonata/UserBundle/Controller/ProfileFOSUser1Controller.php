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

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 *
 * This class is inspired from the FOS Profile Controller, except :
 *   - only twig is supported
 *   - separation of the user authentication form with the profile form.
 */
class ProfileFOSUser1Controller extends Controller
{
    protected $container;
    private $alias;
    private $id;
    private $extraParams;
    private $linkUrlParams;
    private $page;
    private $publishStates;
    private $userName;
    private $settings;
    private $serveMobile;
    private $userRole;
    private $enableHTTPCache;
    private $logged_username;
    private $logged_user;
    private $isAjaxRequest;

    // Override the ContainerAware setContainer to accommodate the extra variables
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;

        // Setting the scoped variables required for the rendering of the page
        $this->alias = null;
        $this->id = null;
        $this->extraParams = null;
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

        // Check if request was Ajax based
        $this->isAjaxRequest = $this->get('bardiscms_page.services.ajax_detection')->isAjaxRequest();

        // Set the publish status that is available for the user
        // Very basic ACL permission check
        if ($this->userRole == "") {
            $this->publishStates = array(1);
        } else {
            $this->publishStates = array(1, 2);
        }

        // Get the logged user if any
        $this->logged_user = $this->get('sonata_user.services.helpers')->getLoggedUser();
        if (is_object($this->logged_user) && $this->logged_user instanceof UserInterface) {
            $this->logged_username = $this->logged_user->getUsername();
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
    public function showAction($alias = 'profile', $userName = null)
    {
        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($alias);

        if($userName === null){
            $userName = $this->logged_username;
        }

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

        // Get the details of the requested user
        $pageData = array(
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'profile_owner' => false,
            'page_username' => $userName,
            'logged_username' => $this->logged_username,
            'page_user' => $this->container->get('sonata_user.services.helpers')->getUserByUsername($userName),
            'blocks' => $this->container->getParameter('sonata.user.configuration.profile_blocks'),
        );

        // Owner user private profile
        if ($userName === $this->logged_username) {
            $pageData['profile_owner'] = true;
        }

        // Render login page
        $response = $this->render('SonataUserBundle:Profile:show.html.twig', $pageData);
        // $response = $this->container->get('templating')->renderResponse('SonataUserBundle:Profile:show.html.twig', $pageData);

        return $response;
    }

    /**
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function editAuthenticationAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("edit-profile");

        if (!$this->page) {
            return $this->render404Page();
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
            'mobile' => $this->serveMobile,
            'logged_username' => $this->logged_username
        );

        // Render register page
        $response = $this->render('SonataUserBundle:Profile:edit_authentication.html.twig', $pageData);
        // $response = $this->container->get('templating')->renderResponse('SonataUserBundle:Profile:edit_authentication.html.'.$this->getEngine(), $pageData);

        return $response;
    }

    /**
     * Edit the user
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

        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("edit-profile");

        if (!$this->page) {
            return $this->render404Page();
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
            'page' => $this->page,
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
