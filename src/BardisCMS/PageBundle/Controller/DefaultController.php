<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Controller;

use BardisCMS\PageBundle\Form\Type\ContactFormType;
use BardisCMS\PageBundle\Form\Type\FilterPagesFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

    // Override the ContainerAware setcontainer to accommodate the extra variables
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

        // Set the publish status that is available for the user
        // Very basic ACL permission check
        if ($this->userRole == "") {
            $this->publishStates = array(1);
        } else {
            $this->publishStates = array(1, 2);
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

        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($this->alias);

        if (!$page) {
            return $this->render404Page();
        }

        $this->page = $page;
        $this->id = $this->page->getId();

        return $this->showPageAction();
    }

    // Get the page id based on alias from route and user details from username
    public function userProfileAction($alias, $userName = null, $currentpage = 0, $totalpageitems = 0, Request $request) {

        $this->pageRequest = $request;
        $this->alias = $alias;
        $this->currentpage = $currentpage;
        $this->totalpageitems = $totalpageitems;
        $this->userName = $userName;

        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($this->alias);

        if (!$page || !isset($this->userName) || !$this->get('sonata_user.services.helpers')->getUserByUsername($this->userName)) {
            return $this->render404Page();
        }

        $this->page = $page;
        $this->id = $this->page->getId();

        $this->linkUrlParams = $this->userName;
        $this->extraParams = $this->userName;

        return $this->showPageAction();
    }

    // Display a page based on the id and the render variables from the settings and the routing
    public function showPageAction() {

        // Simple publishing ACL based on publish state and user role
        if ($this->page->getPublishState() == 0) {
            return $this->render404Page();
        }

        if ($this->page->getPublishState() == 2 && $this->userRole == "") {
            return $this->render404Page();
        }

        // Return cached page if enabled
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

            case 'user_profile':
                $response = $this->renderUserProfilePage();
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
                $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page, 'mobile' => $this->serveMobile));
        }

        if ($this->enableHTTPCache) {
            $response = $this->setResponseCacheHeaders($response);
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

        // If the filter form has been submited
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
                'PageBundle_tagged_noslash', array('extraParams' => $this->extraParams), true
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
            $response = $this->setResponseCacheHeaders($response);
        }

        return $response;
    }

    // Get and display the sitemap xsl to style the xml of the sitemap
    public function sitemapxslAction() {

        $response = $this->render('PageBundle:Default:sitemap.xsl.twig');

        return $response;
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

        if ($this->enableHTTPCache) {
            $response = $this->setResponseCacheHeaders($response);
        }

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

        $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page, 'pages' => $pages, 'blogs' => $blog_pages, 'mobile' => $this->serveMobile));

        return $response;
    }


    // Render user profile page type
    protected function renderUserProfilePage() {

        // Get the logged user
        $logged_user = $this->get('sonata_user.services.helpers')->getLoggedUser();

        // Get the details of the requested user
        $user_details_to_show = array(
            'page_username' => $this->userName,
            'logged_username' => '',
            'page_user' => $this->get('sonata_user.services.helpers')->getUserByUsername($this->userName)
        );

        if (!is_object($logged_user) || !$logged_user instanceof UserInterface) {
            // Public profile
            // add logic here
        } else {
            // Private profile
            // add logic here
            $user_details_to_show['logged_username'] = $logged_user->getUsername();
        }

        // Render user profile page type
        $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page, 'mobile' => $this->serveMobile, 'user_details_to_show' => $user_details_to_show));

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
            $pageList = $this->getDoctrine()->getRepository('PageBundle:Page')->getTaggedCategoryItems($categoryIds, $this->id, $this->publishStates, $this->currentpage, $this->totalpageitems, $tagIds);
        } else {
            $pageList = $this->getDoctrine()->getRepository('PageBundle:Page')->getTaggedItems($tagIds, $this->id, $this->publishStates, $this->currentpage, $this->totalpageitems);
        }

        $pages = $pageList['pages'];
        $totalPages = $pageList['totalPages'];

        $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page, 'pages' => $pages, 'totalPages' => $totalPages, 'extraParams' => $this->extraParams, 'currentpage' => $this->currentpage, 'linkUrlParams' => $this->linkUrlParams, 'totalpageitems' => $this->totalpageitems, 'filterForm' => $filterForm->createView(), 'mobile' => $this->serveMobile));

        return $response;
    }

    // Render category list page type
    protected function renderCategoryPage() {
        $tagIds = $this->get('bardiscms_page.services.helpers')->getTagFilterIds($this->page->getTags()->toArray());
        $categoryIds = $this->get('bardiscms_page.services.helpers')->getCategoryFilterIds($this->page->getCategories()->toArray());

        if (!empty($tagIds)) {
            $pageList = $this->getDoctrine()->getRepository('PageBundle:Page')->getTaggedCategoryItems($categoryIds, $this->id, $this->publishStates, $this->currentpage, $this->totalpageitems, $tagIds);
        } else {
            $pageList = $this->getDoctrine()->getRepository('PageBundle:Page')->getCategoryItems($categoryIds, $this->id, $this->publishStates, $this->currentpage, $this->totalpageitems);
        }

        $pages = $pageList['pages'];
        $totalPages = $pageList['totalPages'];

        $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page, 'pages' => $pages, 'totalPages' => $totalPages, 'extraParams' => $this->extraParams, 'currentpage' => $this->currentpage, 'linkUrlParams' => $this->linkUrlParams, 'totalpageitems' => $this->totalpageitems, 'mobile' => $this->serveMobile));

        return $response;
    }

    // Get the contact form page
    protected function processContactForm(Request $request) {

        if (is_object($this->settings)) {
            $websiteTitle = $this->settings->getWebsiteTitle();
        } else {
            $websiteTitle = '';
        }

        $successMsg = '';
        $ajaxForm = $request->get('isAjax');
        if (!isset($ajaxForm) || !$ajaxForm) {
            $ajaxForm = false;
        }

        // Create the form
        $form = $this->createForm(new ContactFormType());

        // If the page has been submitted
        if ($request->getMethod() == 'POST') {

            //Bind the posted form field values
            $form->handleRequest($request);

            if ($form->isValid()) {
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
            if ($ajaxForm) {

                $ajaxFormData = array(
                    'errors' => $errorList,
                    'formMessage' => $formMessage,
                    'hasErrors' => $formHasErrors
                );

                $ajaxFormResponse = new Response(json_encode($ajaxFormData));
                $ajaxFormResponse->headers->set('Content-Type', 'application/json');

                return $ajaxFormResponse;
            } else {
                return $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page, 'form' => $form->createView(), 'ajaxform' => $ajaxForm, 'formMessage' => $formMessage));
            }
        }
        // If the form has not been submitted yet
        else {
            $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page, 'form' => $form->createView(), 'ajaxform' => $ajaxForm));

            if ($this->enableHTTPCache) {
                $response = $this->setResponseCacheHeaders($response);
            }

            return $response;
        }
    }

    // set a custom Cache-Control directives
    protected function setResponseCacheHeaders(Response $response) {

        $response->setPublic();
        $response->setLastModified($this->page->getDateLastModified());
        $response->setVary(array('Accept-Encoding', 'User-Agent'));
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setSharedMaxAge(3600);

        return $response;
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
                //'auth' => ['bardius', 'kemp1313'],
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
            dump($response);
            dump($data);
            dump($statusCode);
        }
        catch (\GuzzleHttp\Exception\ConnectException $e) {
            $req = $e->getRequest();
            $resp = $e->getResponse();
            dump($req);
            dump($resp);
        }
        catch (\GuzzleHttp\Exception\ClientErrorResponseException $e) {
            $req = $e->getRequest();
            $resp = $e->getResponse();
            dump($req);
            dump($resp);
        }
        catch (\GuzzleHttp\Exception\ServerErrorResponseException $e) {
            $req = $e->getRequest();
            $resp = $e->getResponse();
            dump($req);
            dump($resp);
        }
        catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $req = $e->getRequest();
            $resp = $e->getResponse();
            dump($req);
            dump($resp);
        }
        catch(\Exception $e){
            dump($e);
        }
    }
}
