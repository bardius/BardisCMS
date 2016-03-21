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

use BardisCMS\PageBundle\Form\Type\ContactFormType;
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

        // TODO: MAke the sample Guzzle call to be a service
        // Sample Guzzle call
        //$this->sampleGuzzleCall();

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

        return $this->showPageAction();
    }

    // Display a page based on the id and the render variables from the settings and the routing
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
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(null, $this->page->getDateLastModified(), false, 3600);

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

        // Render the correct view depending on pagetype
        return $this->renderPage();
    }

    // Get the required data to display to the correct view depending on pagetype
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
                $response = $this->render('PageBundle:Default:page.html.twig', array(
                    'page' => $this->page,
                    'logged_username' => $this->userName,
                    'mobile' => $this->serveMobile
                ));
        }

        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                $response,
                $this->page->getDateLastModified(),
                false,
                3600
            );
        }

        return $response;
    }

    // Get and format the filtering arguments to use with the actions
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

    // Get and display all items from all bundles in the sitemap xml
    public function sitemapAction() {

        $page_pages = $this->getDoctrine()->getRepository('PageBundle:Page')->getSitemapList($this->publishStates);
        $blog_pages = $this->getDoctrine()->getRepository('BlogBundle:Blog')->getSitemapList($this->publishStates);

        $sitemapList = array_merge($page_pages, $blog_pages);

        $response = $this->render('PageBundle:Default:sitemap.xml.twig', array('sitemapList' => $sitemapList));

        if ($this->enableHTTPCache) {
            $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                $response,
                $this->page->getDateLastModified(),
                false,
                3600
            );
        }

        return $response;
    }

    // Get and display the sitemap xsl to style the xml of the sitemap
    public function sitemapxslAction() {

        $response = $this->render('PageBundle:Default:sitemap.xsl.twig');

        return $response;
    }

    // Render the home page
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

        $response = $this->render('PageBundle:Default:page.html.twig', array(
            'page' => $this->page,
            'pages' => $pages,
            'blogs' => $blog_pages,
            'logged_username' => $this->userName,
            'mobile' => $this->serveMobile
        ));

        return $response;
    }

    // Render tag list page type
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

        $response = $this->render('PageBundle:Default:page.html.twig', array(
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
        ));

        return $response;
    }

    // Render category list page type
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

        $response = $this->render('PageBundle:Default:page.html.twig', array(
            'page' => $this->page,
            'pages' => $pages,
            'totalPages' => $totalPages,
            'extraParams' => $this->extraParams,
            'currentpage' => $this->currentpage,
            'linkUrlParams' => $this->linkUrlParams,
            'totalpageitems' => $this->totalpageitems,
            'logged_username' => $this->userName,
            'mobile' => $this->serveMobile
        ));

        return $response;
    }

    // TODO: move the contact form process action to a handler service
    // Get the contact form page
    protected function processContactForm(Request $request) {

        if (is_object($this->settings)) {
            $websiteTitle = $this->settings->getWebsiteTitle();
        } else {
            $websiteTitle = '';
        }

        $successMsg = '';

        // Create the form
        $form = $this->createForm(new ContactFormType());

        // If the page has been submitted
        if ($request->getMethod() == 'POST') {

            //Bind the posted form field values
            $form->handleRequest($request);

            if ($form->isValid()) {
                // TODO: split the email sending to a service
                // Get the field values
                $emailData = $form->getData();

                // If data is valid send the email with the twig email template set in the views
                $message = \Swift_Message::newInstance()
                        ->setSubject('Enquiry from ' . $websiteTitle . ' website: ' . $emailData['firstname'] . ' ' . $emailData['surname'] - $emailData['email'])
                        ->setFrom($this->settings->getEmailSender())
                        ->setReplyTo($emailData['email'])
                        ->setTo($this->settings->getEmailRecepient())
                        ->setBody($this->renderView('PageBundle:Email:contactFormEmail.txt.twig', array('sender' => $emailData['firstname'] . ' ' . $emailData['surname'], 'mailData' => $emailData['comment'])));

                // The response for the user upon successful submission
                $successMsg = 'Thank you for contacting us, we will be in touch soon';
                $formMessage = $successMsg;
                $errorList = array();
                $formHasErrors = false;

                // Send the email with php swift mailer and catch errors
                try {
                    $this->get('mailer')->send($message);
                } catch (\Swift_TransportException $exception) {
                    // The response for the user upon unsuccessful mailer send
                    $formMessage = $exception->getMessage();
                    $formHasErrors = true;
                }
            } else {
                // Validate the data and get errors
                $successMsg = '';
                $errorList = $this->get('bardiscms_page.services.helpers')->getFormErrorMessages($form);
                $formMessage = 'There was an error submitting your form. Please try again.';
                $formHasErrors = true;
            }

            // Return the response to the user
            if ($this->isAjaxRequest) {

                $ajaxFormData = array(
                    'errors' => $errorList,
                    'formMessage' => $formMessage,
                    'hasErrors' => $formHasErrors
                );

                $ajaxFormResponse = new Response(json_encode($ajaxFormData));
                $ajaxFormResponse->headers->set('Content-Type', 'application/json');

                return $ajaxFormResponse;
            } else {
                return $this->render('PageBundle:Default:page.html.twig', array(
                    'page' => $this->page,
                    'form' => $form->createView(),
                    'ajaxform' => $this->isAjaxRequest,
                    'formMessage' => $formMessage,
                    'logged_username' => $this->userName,
                    'mobile' => $this->serveMobile
                ));
            }
        }
        // If the form has not been submitted yet
        else {
            $response = $this->render('PageBundle:Default:page.html.twig', array(
                'page' => $this->page,
                'form' => $form->createView(),
                'ajaxform' => $this->isAjaxRequest,
                'logged_username' => $this->userName,
                'mobile' => $this->serveMobile
            ));

            if ($this->enableHTTPCache) {
                $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                    $response,
                    $this->page->getDateLastModified(),
                    false,
                    3600
                );
            }

            return $response;
        }
    }

    // Sort homepage items by the pageOrder value of the objects returned after the merge
    protected function sortHomepageItemsCompare($introItemA, $introItemB) {
        if ($introItemA->getPageOrder() == $introItemB->getPageOrder()) {
            return 0;
        }
        return ($introItemA->getPageOrder() < $introItemB->getPageOrder()) ? -1 : 1;
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
