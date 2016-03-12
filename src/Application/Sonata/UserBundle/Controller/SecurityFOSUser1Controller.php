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


use Sonata\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityFOSUser1Controller extends Controller
{
    // Adding variables required for the rendering of pages
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
        $logged_user = $this->get('sonata_user.services.helpers')->getLoggedUser();
        if (is_object($logged_user) && $logged_user instanceof UserInterface) {
            $this->logged_username = $logged_user->getUsername();
        }
    }

    public function loginAction()
    {

        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($user instanceof UserInterface) {
            $this->container->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');
            $url = $this->container->get('router')->generate('sonata_user_profile_show');

            if($this->isAjaxRequest) {
                return $this->onAjaxSuccess($url);
            }

            return new RedirectResponse($url);
        }

        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("login");

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

        // Return cached page if enabled
        // TODO: check if the login page should never be cached or not before allowing cache here
        /*
        if ($this->enableHTTPCache) {

            $response = $this->setResponseCacheHeaders(new Response());

            if (!$response->isNotModified($this->pageRequest)) {
                // Marks the Response stale
                $response->expire();
            } else {
                // return the 304 Response immediately
                return $response;
            }
        }
        */

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $request = $this->container->get('request');
        $session = $request->getSession();

        // Get the login error if any
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
        if ($error) {
            // If the request was Ajax based and the registration was not successfull
            if($this->isAjaxRequest){
                return $this->onAjaxError($error);
            }

            $error = $error->getMessage();
        }

        // Get last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->has('form.csrf_provider') ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate') : null;

        $pageData = array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->logged_username
        );

        // Render login page
        $response = $this->render('SonataUserBundle:Security:login.html.twig', $pageData);

        // TODO: check if the login page should never be cached or not before allowing cache here
        /*
        if ($this->enableHTTPCache) {
            $response = $this->setResponseCacheHeaders($response);
        }
        */

        return $response;
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

    /**
     * Extend with new method to handle Ajax response with errors
     *
     * @param string $error
     *
     * @return Response
     */
    protected function onAjaxError($error)
    {
        $errorList = array($error->getMessage());
        $formMessage = $this->get('translator')->trans(
            $error->getMessage(),
            array(),
            "SonataUserBundle"
        );
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
     * @param String $url
     *
     * @return Response
     */
    protected function onAjaxSuccess($url)
    {
        $errorList = array();
        $formMessage = '';
        $formHasErrors = false;
        $redirectURL = $url;

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
