<?php

/*
 * Sonata User Bundle Extends
 * This file is part of the BardisCMS.
 * List the Sonata User profiles
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Application\Sonata\UserBundle\Form\Handler\FilterUsersFormHandler;

use BardisCMS\PageBundle\Entity\Page as Page;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller managing the user profile list and search
 */
class ProfileListController extends Controller
{
    protected $container;
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
    private $logged_user;
    private $isAjaxRequest;
    private $userManager;
    private $userSearchTerm;

    const PROFILE_PAGE_LIST_ALIAS = "profile/list";

    /**
     * ContainerAware setContainer to accommodate the extra variables
     *
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;

        // Using custom repository and user entity manager
        $this->userManager = $this->get('fos_user.user_manager');

        // Setting the scoped variables required for the rendering of the page
        $this->extraParams = null;
        $this->currentpage = null;
        $this->totalpageitems = null;
        $this->linkUrlParams = null;
        $this->page = null;
        $this->userName = null;
        $this->userSearchTerm = null;

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
     * Show the paginated users listing
     *
     * @param String $alias
     * @param Int $currentpage
     * @param Int $totalpageitems
     * @param String $extraParams
     *
     * @return Response response
     */
    public function showAction($alias = PROFILE_PAGE_LIST_ALIAS, $currentpage = 0, $totalpageitems = 0, $extraParams = '') {
        $this->extraParams = $extraParams;
        $this->linkUrlParams = $extraParams;
        $this->currentpage = $currentpage;
        $this->totalpageitems = $totalpageitems;
        $this->userSearchTerm = $extraParams;

        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($alias);

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

        // Set the pagination variables
        if (is_object($this->settings)) {
            if (!$this->totalpageitems) {
                $this->totalpageitems = $this->settings->getUsersPerPage();
            }
        }

        $this->totalpageitems = $this->totalpageitems > 0 ? $this->totalpageitems : 20;

        // Getting all the users paginated
        $users = [];
        $totalPages = 0;

        // Check if public profile listings are allowed
        if (is_object($this->settings)) {
            $isPublicProfilesAllowed = $this->settings->getisPublicProfilesAllowed();
        }
        else {
            $isPublicProfilesAllowed = false;
        }

        // Processing the user filter form
        $filterUsersForm = $this->container->get('sonata_user.filter_users.form');
        $filterUsersFormHandler = $this->container->get('sonata_user.filter_users.form.handler');

        $process = $filterUsersFormHandler->process();
        if ($process) {
            $this->userSearchTerm = $filterUsersForm->getData()->getUsername();

            $url = $this->container->get('router')->generate('sonata_user_profile_list_noslash', array(
                'currentpage' => 0,
                'totalpageitems' => $this->totalpageitems,
                'extraParams' => $this->userSearchTerm
            ));

            // If the request was Ajax based and the search term validated with success
            if($this->isAjaxRequest){
                return $this->onAjaxSuccess($url);
            }

            $response = new RedirectResponse($url);

            return $response;
        }
        else {
            // If the request was Ajax based and the search was not successful
            if($this->isAjaxRequest){
                return $this->onAjaxError($filterUsersFormHandler);
            }
        }

        // Get the paginated results of the filtered users
        if($this->userName || $isPublicProfilesAllowed){
            $usersList = $this->userManager->getAllUsersPaginated(
                $this->currentpage,
                $this->totalpageitems,
                $this->userSearchTerm,
                [  $this->userName ? $this->logged_user->getId() : 0 ]
            );

            $users = $usersList['pages'];
            $totalPages = $usersList['totalPages'];
        }

        // Set the default value in the search input
        if ($this->getRequest()->isMethod('GET')) {
            $filterUsersForm->get('username')->setData($this->userSearchTerm);
        }

        $response = new Response();
        $response->sendHeaders();

        $template = $this->renderView('SonataUserBundle:Profile:profile_list.html.twig', array(
            'page' => $this->page,
            'users' => $users,
            'totalPages' => $totalPages,
            'extraParams' => $this->extraParams,
            'userSearchTerm' => $this->userSearchTerm,
            'currentpage' => $this->currentpage,
            'linkUrlParams' => $this->linkUrlParams,
            'totalpageitems' => $this->totalpageitems,
            'logged_username' => $this->userName,
            'filterUsersForm' => $filterUsersForm->createView(),
            'isPublicProfilesAllowed' => $isPublicProfilesAllowed,
            'mobile' => $this->serveMobile
        ), $response);

        $response->setContent($template);

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
     * Extend with new method to handle Ajax response with errors
     *
     * @param FilterUsersFormHandler $formHandler
     *
     * @return Response
     */
    protected function onAjaxError(FilterUsersFormHandler $formHandler)
    {
        $errorList = $formHandler->getErrors();
        $formMessage = $this->get('translator')->trans('form.search.error', array(), 'SonataUserBundle');
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
}
