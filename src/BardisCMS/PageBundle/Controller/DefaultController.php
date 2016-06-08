<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

use FOS\UserBundle\Model\UserInterface;

use BardisCMS\PageBundle\Entity\Page as Page;

use BardisCMS\PageBundle\Form\Type\FilterPagesFormType;

class DefaultController extends Controller {

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

        // Get the settings from setting bundle
        $this->settings = $this->get('bardiscms_settings.load_settings')->loadSettings();

        // Get the highest user role security permission
        $this->userRole = $this->get('sonata_user.services.helpers')->getLoggedUserHighestRole();

        // Check if mobile content should be served
        $this->serveMobile = $this->get('bardiscms_mobile_detect.device_detection')->testMobile();

        // Set the flag for allowing HTTP cache
        // $this->enableHTTPCache = $this->container->getParameter('kernel.environment') == 'prod' && $this->settings->getActivateHttpCache();
        $this->enableHTTPCache = $this->settings->getActivateHttpCache();

        // Check if request was Ajax based
        $this->isAjaxRequest = $this->get('bardiscms_page.services.ajax_detection')->isAjaxRequest();

        // Set the publish statuses that are available for the user
        $this->publishStates = $this->get('bardiscms_page.services.helpers')->getAllowedPublishStates($this->userRole);

        // Get the logged user if any
        $this->logged_user = $this->get('sonata_user.services.helpers')->getLoggedUser();
        if (is_object($this->logged_user) && $this->logged_user instanceof UserInterface) {
            $this->userName = $this->logged_user->getUsername();
        }

        // TODO: MAke the sample Guzzle call to be a service
        // Sample Guzzle call
        //$this->sampleGuzzleCall();
    }

    // Get the page id based on alias from route
    public function aliasAction($alias = '/', $extraParams = null, $currentpage = 0, $totalpageitems = 0, Request $request) {

        $this->pageRequest = $request;
        $this->alias = $alias;
        $this->extraParams = $extraParams;
        $this->linkUrlParams = $extraParams;
        $this->currentpage = $currentpage;
        $this->totalpageitems = $totalpageitems;

        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($this->alias);

        if (!$this->page) {
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }

        $this->id = $this->page->getId();

        $response = $this->showPageAction();

        return $response;
    }

    /**
     * Render a page based on the id and the render variables from the settings and the routing
     *
     * @return Response
     */
    public function showPageAction() {

        // Simple publishing ACL based on publish state and user Allowed Publish States
        $accessAllowedForUserRole = $this->get('bardiscms_page.services.helpers')->isUserAccessAllowedByRole(
            $this->page->getPublishState(),
            $this->publishStates
        );
        if(!$accessAllowedForUserRole){
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_401);
        }

        // Return cached page if enabled
        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                null,
                $this->page->getDateLastModified(),
                $this->userName ? true : false,
                3600
            );

            if (!$response->isNotModified($this->pageRequest)) {
                // Marks the Response stale
                $response->expire();
            } else {
                // return the 304 Response immediately
                return $response;
            }
        }

        // Set the website settings and metatags
        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        // Set the pagination variables
        if (is_object($this->settings)) {
            if (!$this->totalpageitems) {
                $this->totalpageitems = $this->settings->getItemsPerPage();
            }
        } else {
            $this->totalpageitems = 10;
        }

        return $this->renderPage();
    }

    /**
     * Render the proper action/view depending on pagetype
     *
     * @return Response
     */
    protected function renderPage() {

        switch ($this->page->getPagetype()) {

            case 'category_page':
                $response = $this->renderCategoryPage();
                break;

            case 'page_tag_list':
                $response = $this->renderTagListPage();
                break;

            case 'homepage':
                $response = $this->renderHomePage();
                break;

            case 'contact':
                // Render contact page type
                $response = $this->processContactForm($this->pageRequest);
                break;

            default:
                // Render normal page type
                $response = new Response();

                if ($this->enableHTTPCache) {
                    $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                        $response,
                        $this->page->getDateLastModified(),
                        $this->userName ? true : false,
                        3600
                    );

                    $response->sendHeaders();
                }

                $template = $this->renderView('PageBundle:Default:page.html.twig', array(
                    'page' => $this->page,
                    'logged_username' => $this->userName,
                    'mobile' => $this->serveMobile
                ), $response);

                $response->setContent($template);
        }

        return $response;
    }

    /**
     * Get and normalise the filtering arguments to use with the actions
     *
     * @param Request $request
     *
     * @return Response
     */
    public function filterPagesAction(Request $request) {

        $filterTags = 'all';
        $filterCategories = 'all';

        // Create the filters form
        $filterForm = $this->createForm(new FilterPagesFormType());
        $filterData = null;

        // If the filter form has been submitted
        if ($request->getMethod() == 'POST') {

            // Bind the data with the form
            $filterForm->handleRequest($request);

            // Get the data from the form
            $filterData = $filterForm->getData();

            // Assign the filters to categories and tags
            $filterTags = $this->get('bardiscms_page.services.helpers')->getTagFilterTitles($filterData['tags']);
            $filterCategories = $this->get('bardiscms_page.services.helpers')->getCategoryFilterTitles($filterData['categories']);
        }

        // Use the filters based on the routing structure
        $this->extraParams = urlencode($filterTags) . '|' . urlencode($filterCategories);

        // Generate the proper route for the required results
        $url = $this->get('router')->generate(
            'PageBundle_tagged_noslash',
            array('extraParams' => $this->extraParams),
            true
        );

        // Redirect to the results
        return $this->redirect($url);
    }

    /**
     * Get and display all items from all bundles in the sitemap xml
     *
     * @return Response
     */
    public function sitemapAction() {

        $page_pages = $this->getDoctrine()->getRepository('PageBundle:Page')->getSitemapList($this->publishStates);
        $blog_pages = $this->getDoctrine()->getRepository('BlogBundle:Blog')->getSitemapList($this->publishStates);

        $sitemapList = array_merge($page_pages, $blog_pages);

        $response = new Response();

        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                $response,
                $this->page->getDateLastModified(),
                $this->userName ? true : false,
                3600
            );

            $response->sendHeaders();
        }

        $template = $this->renderView('PageBundle:Default:sitemap.xml.twig', array(
            'sitemapList' => $sitemapList
        ), $response);

        $response->setContent($template);

        return $response;
    }

    /**
     * Render the sitemap xsl to style the xml of the sitemap
     *
     * @return Response
     */
    public function sitemapxslAction() {
        $response = new Response();

        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                $response,
                $this->page->getDateLastModified(),
                $this->userName ? true : false,
                3600
            );

            $response->sendHeaders();
        }

        $template = $this->renderView('PageBundle:Default:sitemap.xsl.twig', array(), $response);

        $response->setContent($template);

        return $response;
    }

    /**
     * Render the home page page type
     *
     * @return Response
     */
    protected function renderHomePage() {

        // Render homepage page type
        $categoryIds = $this->get('bardiscms_page.services.helpers')->getCategoryFilterIds($this->page->getCategories()->toArray());

        // Get the items to display in homepage from all bundles that should supply contents
        // Get the pages for the category id of homepage but take ou the current (homepage) page item from the results
        $page_pages = $this->getDoctrine()->getRepository('PageBundle:Page')->getHomepageItems($categoryIds, $this->id, $this->publishStates);
        $blog_pages = $this->getDoctrine()->getRepository('BlogBundle:Blog')->getHomepageItems($categoryIds, $this->publishStates);

        $pages = array_merge($page_pages, $blog_pages);

        // Sort all the items based on custom sorting
        usort($pages, array("BardisCMS\PageBundle\Controller\DefaultController", "sortHomepageItemsCompare"));

        $response = new Response();

        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                $response,
                $this->page->getDateLastModified(),
                $this->userName ? true : false,
                3600
            );

            $response->sendHeaders();
        }

        $template  = $this->renderView('PageBundle:Default:page.html.twig', array(
            'page' => $this->page,
            'pages' => $pages,
            'blogs' => $blog_pages,
            'logged_username' => $this->userName,
            'mobile' => $this->serveMobile
        ), $response);

        $response->setContent($template);

        return $response;
    }

    /**
     * Render tag list page type
     *
     * @return Response
     */
    protected function renderTagListPage() {

        $filterForm = $this->createForm(new FilterPagesFormType());
        $filterData = $this->get('bardiscms_page.services.helpers')->getRequestedFilters($this->extraParams);
        $tagIds = $this->get('bardiscms_page.services.helpers')->getTagFilterIds($filterData['tags']->toArray());
        $categoryIds = $this->get('bardiscms_page.services.helpers')->getCategoryFilterIds($filterData['categories']->toArray());

        $filterForm->setData($filterData);

        if (!empty($categoryIds)) {
            $pageList = $this->getDoctrine()->getRepository('PageBundle:Page')->getTaggedCategoryItems(
                $categoryIds,
                $this->id,
                $this->publishStates,
                $this->currentpage,
                $this->totalpageitems,
                $tagIds
            );
        } else {
            $pageList = $this->getDoctrine()->getRepository('PageBundle:Page')->getTaggedItems(
                $tagIds,
                $this->id,
                $this->publishStates,
                $this->currentpage,
                $this->totalpageitems
            );
        }

        $pages = $pageList['pages'];
        $totalPages = $pageList['totalPages'];

        $response = new Response();

        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                $response,
                $this->page->getDateLastModified(),
                $this->userName ? true : false,
                3600
            );

            $response->sendHeaders();
        }

        $template = $this->renderView('PageBundle:Default:page.html.twig', array(
            'page' => $this->page,
            'pages' => $pages,
            'totalPages' => $totalPages,
            'extraParams' => $this->extraParams,
            'currentpage' => $this->currentpage,
            'linkUrlParams' => $this->linkUrlParams,
            'totalpageitems' => $this->totalpageitems,
            'filterForm' => $filterForm->createView(),
            'logged_username' => $this->userName,
            'mobile' => $this->serveMobile
        ), $response);

        $response->setContent($template);

        return $response;
    }

    /**
     * Render category list page type
     *
     * @return Response
     */
    protected function renderCategoryPage() {
        $tagIds = $this->get('bardiscms_page.services.helpers')->getTagFilterIds($this->page->getTags()->toArray());
        $categoryIds = $this->get('bardiscms_page.services.helpers')->getCategoryFilterIds($this->page->getCategories()->toArray());

        if (!empty($tagIds)) {
            $pageList = $this->getDoctrine()->getRepository('PageBundle:Page')->getTaggedCategoryItems(
                $categoryIds,
                $this->id,
                $this->publishStates,
                $this->currentpage,
                $this->totalpageitems,
                $tagIds
            );
        } else {
            $pageList = $this->getDoctrine()->getRepository('PageBundle:Page')->getCategoryItems(
                $categoryIds,
                $this->id,
                $this->publishStates,
                $this->currentpage,
                $this->totalpageitems
            );
        }

        $pages = $pageList['pages'];
        $totalPages = $pageList['totalPages'];

        $response = new Response();

        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                $response,
                $this->page->getDateLastModified(),
                $this->userName ? true : false,
                3600
            );

            $response->sendHeaders();
        }

        $template = $this->renderView('PageBundle:Default:page.html.twig', array(
            'page' => $this->page,
            'pages' => $pages,
            'totalPages' => $totalPages,
            'extraParams' => $this->extraParams,
            'currentpage' => $this->currentpage,
            'linkUrlParams' => $this->linkUrlParams,
            'totalpageitems' => $this->totalpageitems,
            'logged_username' => $this->userName,
            'mobile' => $this->serveMobile
        ), $response);

        $response->setContent($template);

        return $response;
    }

    /**
     * render and handle the contact form page
     *
     * @param Request $request
     *
     * @return Response
     */
    protected function processContactForm(Request $request) {
        $formMessage = null;
        $errorList = null;
        $formHasErrors = false;

        // Contact Form
        $contactForm = $this->container->get('bardiscms_page.contact.form');
        $contactFormHandler = $this->container->get('bardiscms_page.contact.form.handler');

        // If the Contact Form has been submitted
        if ($request->getMethod() == 'POST') {
            $contactFormProcess = $contactFormHandler->process();

            // Validate the data and get errors if any
            if ($contactFormProcess) {
                $formMessage = $this->container->get('translator')->trans('contact_form.response.success', array(), 'BardisCMSPageBundle');
                $errorList = array();
                $formHasErrors = false;
            }
            else {
                $formMessage = $this->container->get('translator')->trans('contact_form.response.error', array(), 'BardisCMSPageBundle');
                $errorList = $this->get('bardiscms_page.services.helpers')->getFormErrorMessages($contactForm);
                $formHasErrors = true;
            }

            // If the request was Ajax based
            if($this->isAjaxRequest){
                if ($contactFormProcess) {
                    return $this->onAjaxSuccess('contact.flash.success');
                } else {
                    return $this->onAjaxError($contactFormHandler);
                }
            }
        }

        $response = new Response();

        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                $response,
                $this->page->getDateLastModified(),
                $this->userName ? true : false,
                3600
            );

            $response->sendHeaders();
        }

        $template = $this->renderView('PageBundle:Default:page.html.twig', array(
            'page' => $this->page,
            'form' => $contactForm->createView(),
            'ajaxform' => $this->isAjaxRequest,
            'formMessage' => $formMessage,
            'errorList' => $errorList,
            'formHasErrors' => $formHasErrors,
            'logged_username' => $this->userName,
            'mobile' => $this->serveMobile
        ), $response);

        $response->setContent($template);

        return $response;
    }

    /**
     * Sort homepage items by the pageOrder value
     *
     * @param $introItemA
     * @param $introItemB
     *
     * @return integer
     */
    protected function sortHomepageItemsCompare($introItemA, $introItemB) {
        if ($introItemA->getPageOrder() == $introItemB->getPageOrder()) {
            return 0;
        }
        return ($introItemA->getPageOrder() < $introItemB->getPageOrder()) ? -1 : 1;
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
        $formMessage = 'contact_form.response.error';
        $formHasErrors = true;

        $ajaxFormData = array(
            'errors' => $errorList,
            'formMessage' => $this->container->get('translator')->trans($formMessage, array(), 'BardisCMSPageBundle'),
            'hasErrors' => $formHasErrors
        );

        $ajaxFormResponse = new Response(json_encode($ajaxFormData));
        $ajaxFormResponse->headers->set('Content-Type', 'application/json');

        return $ajaxFormResponse;
    }

    /**
     * Extend with new method to handle Ajax response with success
     *
     * @return Response
     */
    protected function onAjaxSuccess()
    {
        $errorList = array();
        $formMessage = 'contact_form.response.success';
        $formHasErrors = false;

        $ajaxFormData = array(
            'errors' => $errorList,
            'formMessage' => $this->container->get('translator')->trans($formMessage, array(), 'BardisCMSPageBundle'),
            'hasErrors' => $formHasErrors
        );

        $ajaxFormResponse = new Response(json_encode($ajaxFormData));
        $ajaxFormResponse->headers->set('Content-Type', 'application/json');

        return $ajaxFormResponse;
    }

    // Sample Guzzle Call
    protected function sampleGuzzleCall(){
        // Sample Guzzle Client Service
        // http://docs.guzzlephp.org/en/latest/
        try {
            $sampleGuzzleAPIClient = $this->get('guzzle.client.github');

            // Sample API endpoint URL
            $sampleAPIClientURL = "/repos/bardius/BardisCMS/commits";

            // Sample GET request
            $response = $sampleGuzzleAPIClient->request('GET', $sampleAPIClientURL, [
                'headers' => [
                //    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                //    'Sample-Header' => 'BardisCMS'
                ],
                //'proxy' => [
                //      'http'  => 'tcp://localhost:8125', // Use this proxy with "http"
                //      'https' => 'tcp://localhost:9124', // Use this proxy with "https",
                //      'no' => ['.mit.edu', 'foo.com']    // Don't use a proxy with these
                //],
                //'allow_redirects' => false,
                //'auth' => ['username', 'password'],
                'query' => [
                    'author' => 'bardius'
                ],
                'http_errors' => false,
                'connect_timeout' => 30,
                'timeout' => 30
            ]);

            // Sample POST request with form parameters or json as body
            //$response = $sampleGuzzleAPIClient->request('POST', $sampleAPIClientURL, [
                //'headers' => [
                //  'Accept' => 'application/json',
                //  'Content-Type' => 'application/json',
                //  'Sample-Header' => 'BardisCMS'
                //],
                //'proxy' => [
                //    'http'  => 'tcp://localhost:8125', // Use this proxy with "http"
                //    'https' => 'tcp://localhost:9124', // Use this proxy with "https",
                //    'no' => ['.mit.edu', 'foo.com']    // Don't use a proxy with these
                //],
                //'json' => [
                //    'username' => 'bardius',
                //    'project' => 'BardisCMS'
                //],
                //'auth' => ['username', 'password'],
                //'form_params' => [
                //    'username' => 'bardius',
                //    'project' => 'BardisCMS'
                //],
                //'http_errors' => false,
                //'connect_timeout' => 30,
                //'timeout' => 30
           // ]);

            $data = json_decode($response->getBody());
            $statusCode = $response->getStatusCode();
        }
        catch (\GuzzleHttp\Exception\ConnectException $e) {
            $req = $e->getRequest();
            $resp = $e->getResponse();
        }
        catch (\GuzzleHttp\Exception\ClientErrorResponseException $e) {
            $req = $e->getRequest();
            $resp = $e->getResponse();
        }
        catch (\GuzzleHttp\Exception\ServerErrorResponseException $e) {
            $req = $e->getRequest();
            $resp = $e->getResponse();
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $req = $e->getRequest();
            $resp = $e->getResponse();
        }
        catch(\Exception $e){
        }
    }
}
