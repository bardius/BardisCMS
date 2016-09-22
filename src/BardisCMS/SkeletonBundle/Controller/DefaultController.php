<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\SkeletonBundle\Controller;

use BardisCMS\PageBundle\Entity\Page as Page;
use BardisCMS\SkeletonBundle\Form\Type\FilterResultsFormType;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    // Adding variables required for the rendering of pages
    protected $container;
    private $pageRequest;
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
    private $logged_user;
    private $isAjaxRequest;
    private $eTagHash;

    // Override the ContainerAware setcontainer to accomodate the extra variables
    public function setContainer(ContainerInterface $container = null)
    {
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

        // Get the settings from setting bundle
        $this->settings = $this->get('bardiscms_settings.load_settings')->loadSettings();

        // Get the highest user role security permission
        $this->userRole = $this->get('sonata_user.services.helpers')->getLoggedUserHighestRole();

        // Check if mobile content should be served
        $this->serveMobile = $this->get('bardiscms_mobile_detect.device_detection')->testMobile();

        // Set the flag for allowing HHTP cache
        $this->enableHTTPCache = $this->container->getParameter('kernel.environment') === 'prod' && $this->settings->getActivateHttpCache();

        // Check if request was Ajax based
        $this->isAjaxRequest = $this->get('bardiscms_page.services.ajax_detection')->isAjaxRequest();

        // Set the publish statuses that are available for the user
        $this->publishStates = $this->get('bardiscms_skeleton.services.helpers')->getAllowedPublishStates($this->userRole);

        // Get the logged user if any
        $this->logged_user = $this->get('sonata_user.services.helpers')->getLoggedUser();
        if (is_object($this->logged_user) && $this->logged_user instanceof UserInterface) {
            $this->userName = $this->logged_user->getUsername();
        }
    }

    // Get the Skeleton page id based on alias from route
    public function aliasAction($alias, $extraParams, $currentpage, $totalpageitems, Request $request)
    {
        $this->pageRequest = $request;
        $this->alias = $alias;
        $this->extraParams = $extraParams;
        $this->linkUrlParams = $extraParams;
        $this->currentpage = $currentpage;
        $this->totalpageitems = $totalpageitems;

        $this->page = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->findOneByAlias($this->alias);

        if (!$this->page) {
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }

        // Calculate the ETag hash that will be used for the HTTP Headers of the response
        // TODO: calculate the getDateLastModified properly based on the contents of  the page
        $this->eTagHash = $this->get('bardiscms_page.services.etag_header_hash_provider')->getETagHash(
            $this->alias.'?'.$this->extraParams,
            $this->page->getPublishState(),
            $this->page->getDateLastModified(),
            $this->userName
        );

        $this->id = $this->page->getId();

        $response = $this->showPageAction();

        return $response;
    }

    /**
     * Render a page based on the id and the render variables from the settings and the routing.
     *
     * @return Response
     */
    public function showPageAction()
    {

        // Simple publishing ACL based on publish state and user Allowed Publish States
        $accessAllowedForUserRole = $this->get('bardiscms_skeleton.services.helpers')->isUserAccessAllowedByRole(
            $this->page->getPublishState(),
            $this->publishStates
        );

        if (!$accessAllowedForUserRole) {
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_401);
        }

        // Return cached page if reverse proxy cache is enabled
        if ($this->enableHTTPCache) {
            $requestNormalizedETags = $this->get('bardiscms_page.services.etag_header_hash_provider')->getNormalizedETagHashWithGzip($this->pageRequest);
            $invalidationResponse = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                new Response(),
                $this->page->getDateLastModified(),
                $this->eTagHash,
                $this->userName ? true : false,
                $this->userName ? 0 : 360
            );

            if ($invalidationResponse->isNotModified($this->pageRequest)) {
                // Check the ETag header for validation
                // TODO: properly check the ETag against the if_none_match headers
                if ($this->pageRequest->headers->get('if_none_match') && strpos($requestNormalizedETags, $this->eTagHash) !== false) {
                    // Return the 304 Status Response (with empty content) immediately
                    $invalidationResponse->setNotModified();

                    return $invalidationResponse;
                } elseif (!$this->pageRequest->headers->get('if_none_match')) {
                    // Return the 304 Status Response (with empty content) immediately
                    $invalidationResponse->setNotModified();

                    return $invalidationResponse;
                }
            } else {
                // Marks the Response stale in case LastModified header is used for invalidation
                $invalidationResponse->expire();
                // TODO: purge the response from proxies
            }
        }

        // Set the website settings and metaTags
        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        // Set the pagination variables
        if (is_object($this->settings)) {
            if (!$this->totalpageitems) {
                $this->totalpageitems = $this->settings->getItemsPerPage();
            }
        }

        $this->totalpageitems = $this->totalpageitems > 0 ? $this->totalpageitems : 20;

        return $this->renderPage();
    }

    /**
     * Render the proper action/view depending on page type.
     *
     * @return Response
     */
    protected function renderPage()
    {
        switch ($this->page->getPagetype()) {

            case 'category_page':
                $response = $this->renderCategoryPage();
                break;

            case 'skeleton_filtered_list':
                $response = $this->renderFilteredListPage();
                break;

            case 'skeleton_home':
                $response = $this->renderSkeletonHomePage();
                break;

            default:
                // Render normal page type
                $response = new Response();

                if ($this->enableHTTPCache) {
                    $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                        $response,
                        $this->page->getDateLastModified(),
                        $this->eTagHash,
                        $this->userName ? true : false,
                        $this->userName ? 0 : 360
                    );
                }

                $response->setETag($this->eTagHash);
                $response->sendHeaders();

                $template = $this->renderView('SkeletonBundle:Default:page.html.twig', array(
                    'page' => $this->page,
                    'logged_username' => $this->userName,
                    'mobile' => $this->serveMobile,
                ), $response);

                $response->setContent($template);
        }

        return $response;
    }

    /**
     * Get and normalise the filtering arguments to use with the actions.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function filterPagesAction(Request $request)
    {
        $filterTags = 'all';
        $filterCategories = 'all';

        // Create the filters form
        $filterForm = $this->createForm(new FilterResultsFormType());
        $filterData = null;

        // If the filter form has been submitted
        if ($request->getMethod() === 'POST') {

            // Bind the data with the form
            $filterForm->handleRequest($request);

            // Get the data from the form
            $filterData = $filterForm->getData();

            // Assign the filters to categories and tags
            $filterTags = $this->get('bardiscms_skeleton.services.helpers')->getTagFilterTitles($filterData['tags']);
            $filterCategories = $this->get('bardiscms_skeleton.services.helpers')->getCategoryFilterTitles($filterData['categories']);
        }

        // Use the filters based on the routing structure
        $this->extraParams = urlencode($filterTags).'|'.urlencode($filterCategories);

        // Generate the proper route for the required results
        $url = $this->get('router')->generate(
            'SkeletonBundle_tagged_noslash',
            array('extraParams' => $this->extraParams),
            true
        );

        // Redirect to the results
        return $this->redirect($url);
    }

    // Render the home page
    protected function renderSkeletonHomePage()
    {

        // Get all items to display in home page
        $pageList = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->getAllItems($this->id, $this->publishStates, $this->currentpage, $this->totalpageitems);
        $pages = $pageList['pages'];
        $totalPages = $pageList['totalPages'];

        $response = $this->render('SkeletonBundle:Default:page.html.twig', array('page' => $this->page, 'pages' => $pages, 'totalPages' => $totalPages, 'extraParams' => $this->extraParams, 'currentpage' => $this->currentpage, 'linkUrlParams' => $this->linkUrlParams, 'totalpageitems' => $this->totalpageitems, 'mobile' => $this->serveMobile));

        return $response;
    }

    /**
     * Render the home page page type.
     *
     * @return Response
     */
    protected function renderFilteredListPage()
    {
        $filterForm = $this->createForm(new FilterResultsFormType());
        $filterData = $this->get('bardiscms_skeleton.services.helpers')->getRequestedFilters($this->extraParams);
        $tagIds = $this->get('bardiscms_skeleton.services.helpers')->getTagFilterIds($filterData['tags']->toArray());
        $categoryIds = $this->get('bardiscms_skeleton.services.helpers')->getCategoryFilterIds($filterData['categories']->toArray());

        $filterForm->setData($filterData);

        if (!empty($categoryIds)) {
            $pageList = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->getTaggedCategoryItems($categoryIds, $this->id, $this->publishStates, $this->currentpage, $this->totalpageitems, $tagIds);
        } else {
            $pageList = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->getTaggedItems($tagIds, $this->id, $this->publishStates, $this->currentpage, $this->totalpageitems);
        }

        $pages = $pageList['pages'];
        $totalPages = $pageList['totalPages'];

        $response = new Response();

        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                $response,
                $this->page->getDateLastModified(),
                $this->eTagHash,
                $this->userName ? true : false,
                $this->userName ? 0 : 360
            );
        }

        $response->setETag($this->eTagHash);
        $response->sendHeaders();

        $template = $this->renderView('SkeletonBundle:Default:page.html.twig', array(
            'page' => $this->page,
            'pages' => $pages,
            'totalPages' => $totalPages,
            'extraParams' => $this->extraParams,
            'currentpage' => $this->currentpage,
            'linkUrlParams' => $this->linkUrlParams,
            'totalpageitems' => $this->totalpageitems,
            'filterForm' => $filterForm->createView(),
            'logged_username' => $this->userName,
            'mobile' => $this->serveMobile,
        ), $response);

        $response->setContent($template);

        return $response;
    }

    // Render category list page type
    protected function renderCategoryPage()
    {
        $tagIds = $this->get('bardiscms_skeleton.services.helpers')->getTagFilterIds($this->page->getTags()->toArray());
        $categoryIds = $this->get('bardiscms_skeleton.services.helpers')->getCategoryFilterIds($this->page->getCategories()->toArray());

        if (!empty($tagIds)) {
            $pageList = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->getTaggedCategoryItems($categoryIds, $this->id, $this->publishStates, $this->currentpage, $this->totalpageitems, $tagIds);
        } else {
            $pageList = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->getCategoryItems($categoryIds, $this->id, $this->publishStates, $this->currentpage, $this->totalpageitems);
        }

        $pages = $pageList['pages'];
        $totalPages = $pageList['totalPages'];

        $response = new Response();

        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                $response,
                $this->page->getDateLastModified(),
                $this->eTagHash,
                $this->userName ? true : false,
                $this->userName ? 0 : 360
            );
        }

        $response->setETag($this->eTagHash);
        $response->sendHeaders();

        $template = $this->renderView('SkeletonBundle:Default:page.html.twig', array(
            'page' => $this->page,
            'pages' => $pages,
            'totalPages' => $totalPages,
            'extraParams' => $this->extraParams,
            'currentpage' => $this->currentpage,
            'linkUrlParams' => $this->linkUrlParams,
            'totalpageitems' => $this->totalpageitems,
            'logged_username' => $this->userName,
            'mobile' => $this->serveMobile,
        ), $response);

        $response->setContent($template);

        return $response;
    }

    /**
     * Extend with new method to handle Ajax response with errors.
     *
     * @param $formHandler
     *
     * @return Response
     */
    protected function onAjaxError($formHandler)
    {
        $errorList = $formHandler->getErrors();
        $formMessage = 'skeleton_form.response.error';
        $formHasErrors = true;

        $ajaxFormData = array(
            'errors' => $errorList,
            'formMessage' => $this->container->get('translator')->trans($formMessage, array(), 'BardisCMSSkeletonBundle'),
            'hasErrors' => $formHasErrors,
        );

        $ajaxFormResponse = new Response(json_encode($ajaxFormData));
        $ajaxFormResponse->headers->set('Content-Type', 'application/json');

        return $ajaxFormResponse;
    }

    /**
     * Extend with new method to handle Ajax response with success.
     *
     * @return Response
     */
    protected function onAjaxSuccess()
    {
        $errorList = array();
        $formMessage = 'skeleton_form.response.success';
        $formHasErrors = false;

        $ajaxFormData = array(
            'errors' => $errorList,
            'formMessage' => $this->container->get('translator')->trans($formMessage, array(), 'BardisCMSSkeletonBundle'),
            'hasErrors' => $formHasErrors,
        );

        $ajaxFormResponse = new Response(json_encode($ajaxFormData));
        $ajaxFormResponse->headers->set('Content-Type', 'application/json');

        return $ajaxFormResponse;
    }
}
