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
use BardisCMS\PageBundle\Form\FilterPagesForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DefaultController extends Controller {

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

    // Override the ContainerAware setcontainer to accomodate the extra variables
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

        // Set the flag for allowing HHTP cache
        $this->enableHTTPCache = $this->container->getParameter('kernel.environment') == 'prod' && $this->settings->getActivateHttpCache();

        // Set the publish status that is avaliable for the user
        // Very basic ACL permission check
        if ($this->userRole == "") {
            $this->publishStates = array(1);
        } else {
            $this->publishStates = array(1, 2);
        }
    }

    // Get the page id based on alias from route
    public function aliasAction($alias = '/', $extraParams = null, $currentpage = 0, $totalpageitems = 0) {

        $this->alias = $alias;
        $this->extraParams = $extraParams;
        $this->linkUrlParams = $extraParams;
        $this->currentpage = $currentpage;
        $this->totalpageitems = $totalpageitems;

        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($alias);

        if (!$page) {
            return $this->render404Page();
        }

        $this->page = $page;
        $this->id = $this->page->getId();

        return $this->showPageAction();
    }

    // Get the page id based on alias from route and user details from username
    public function userProfileAction($alias, $userName = null, $currentpage = 0, $totalpageitems = 0) {

        $this->alias = $alias;
        $this->currentpage = $currentpage;
        $this->totalpageitems = $totalpageitems;

        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($alias);

        if (!$page || !isset($userName) || !$this->get('sonata_user.services.helpers')->getUserByUsername($userName)) {
            return $this->render404Page();
        }

        $this->page = $page;
        $this->id = $this->page->getId();

        $this->userName = $userName;
        $this->linkUrlParams = $userName;
        $this->extraParams = $userName;

        return $this->showPageAction();
    }

    // Display a page based on the id and the render variables from the settings and the routing
    protected function showPageAction() {

        // Simple publishing ACL based on publish state and user role
        if ($this->page->getPublishState() == 0) {
            return $this->render404Page();
        }

        if ($this->page->getPublishState() == 2 && $this->userRole == "") {
            return $this->render404Page();
        }

        //dump($this->container->getParameter('kernel.environment'));
        
        // Return cached page if enabled
        if ($this->enableHTTPCache) {

            $response = new Response();

            // set a custom Cache-Control directive
            $response->headers->addCacheControlDirective('must-revalidate', true);
            // set multiple vary headers
            $response->setVary(array('Accept-Encoding', 'User-Agent'));
            // create a Response with a Last-Modified header
            $response->setLastModified($this->page->getDateLastModified());
            // Set response as public. Otherwise it will be private by default.
            $response->setPublic();

            //dump($response->isNotModified($this->getRequest()));
            //dump($response->getStatusCode());			
            if (!$response->isNotModified($this->getRequest())) {
                // Marks the Response stale
                $response->expire();
            } else {
                // return the 304 Response immediately
                $response->setSharedMaxAge(3600);
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
                $response = $this->ContactForm($this->getRequest());
                break;

            default:
                // Render normal page type
                $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page, 'mobile' => $this->serveMobile));
        }

        if ($this->enableHTTPCache) {
            // set a custom Cache-Control directive
            $response->setPublic();
            $response->setLastModified($this->page->getDateLastModified());
            $response->setVary(array('Accept-Encoding', 'User-Agent'));
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setSharedMaxAge(3600);
        }

        return $response;
    }

    // Get and format the filtering arguments to use with the actions 
    public function filterPagesAction(Request $request) {

        $filterTags = 'all';
        $filterCategories = 'all';

        // Create the filters form
        $filterForm = $this->createForm(new FilterPagesForm());
        $filterData = null;

        // If the filter form has been submited
        if ($request->getMethod() == 'POST') {

            // Bind the data with the form
            $filterForm->handleRequest($request);

            // Get the data from the form
            $filterData = $filterForm->getData();

            // Assign the filters to categories and tags
            $filterTags = $this->getTagFilterTitles($filterData['tags']);
            $filterCategories = $this->getCategoryFilterTitles($filterData['categories']);
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
            // set a custom Cache-Control directive
            $response->setPublic();
            $response->setVary(array('Accept-Encoding', 'User-Agent'));
            $response->setSharedMaxAge(3600);
        }

        return $response;
    }

    // Get and display the sitemap xsl to style the xml of the sitemap
    public function sitemapxslAction() {

        $response = $this->render('PageBundle:Default:sitemap.xsl.twig');

        return $response;
    }

    // Get and display to the 404 error page
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
            // set a custom Cache-Control directive
            $response->setPublic();
            $response->setLastModified($this->page->getDateLastModified());
            $response->setVary(array('Accept-Encoding', 'User-Agent'));
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setSharedMaxAge(3600);
        }

        return $response;
    }

    // Get and display to the home page
    protected function renderHomePage() {

        // Render homepage page type
        $categoryIds = $this->getCategoryFilterIds($this->page->getCategories()->toArray());

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

    protected function renderUserProfilePage() {

        // Get the logged user
        $logged_user = $this->get('sonata_user.services.helpers')->getLoggedUser();

        // Get the details of the requested user
        $userName = $this->extraParams;
        $user_details_to_show = array(
            'page_username' => $userName,
            'logged_username' => '',
            'page_user' => $this->get('sonata_user.services.helpers')->getUserByUsername($userName)
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

    protected function renderTagListPage() {

        // Render tag list page type
        $filterForm = $this->createForm(new FilterPagesForm());
        $filterData = $this->getRequestedFilters($this->extraParams);
        $tagIds = $this->getTagFilterIds($filterData['tags']->toArray());
        $categoryIds = $this->getCategoryFilterIds($filterData['categories']->toArray());

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

    protected function renderCategoryPage() {

        // Render category list page type
        $tagIds = $this->getTagFilterIds($this->page->getTags()->toArray());
        $categoryIds = $this->getCategoryFilterIds($this->page->getCategories()->toArray());

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
    protected function contactForm(Request $request) {

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

        // If the page has been submited
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

                // The responce for the user upon successful submission
                $successMsg = 'Thank you for contacting us, we will be in touch soon';
                $formMessage = $successMsg;
                $errorList = array();
                $formhasErrors = false;

                // Send the email with php swift mailerand catch errors
                try {
                    $this->get('mailer')->send($message);
                } catch (\Swift_TransportException $exception) {
                    // The responce for the user upon unsuccessful mailer send
                    $formMessage = $exception->getMessage();
                    $formhasErrors = true;
                }
            } else {
                // Validate the data and get errors
                $successMsg = '';
                $errorList = $this->getFormErrorMessages($form);
                $formMessage = 'There was an error submitting your form. Please try again.';
                $formhasErrors = true;
            }

            // Return the responce to the user
            if ($ajaxForm) {

                $ajaxFormData = array(
                    'errors' => $errorList,
                    'formMessage' => $formMessage,
                    'hasErrors' => $formhasErrors
                );

                $ajaxFormResponce = new Response(json_encode($ajaxFormData));
                $ajaxFormResponce->headers->set('Content-Type', 'application/json');

                return $ajaxFormResponce;
            } else {
                return $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page, 'form' => $form->createView(), 'ajaxform' => $ajaxForm, 'formMessage' => $formMessage));
            }
        }
        // If the form has not been submited yet
        else {
            $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page, 'form' => $form->createView(), 'ajaxform' => $ajaxForm));

            if ($this->container->getParameter('kernel.environment') == 'prod') {
                // set a custom Cache-Control directive
                $response->setPublic();
                $response->setLastModified($this->page->getDateLastModified());
                $response->setVary(array('Accept-Encoding', 'User-Agent'));
                $response->headers->addCacheControlDirective('must-revalidate', true);
                $response->setSharedMaxAge(3600);
            }

            return $response;
        }
    }

    // Get the error messages of the contact form assosiated with their fields in an array
    private function getFormErrorMessages(\Symfony\Component\Form\Form $form) {

        $errors = array();
        $formErrors = iterator_to_array($form->getErrors(false, true));

        foreach ($formErrors as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errors[$key] = $template;
        }
        if ($form->count()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getFormErrorMessages($child);
                }
            }
        }
        return $errors;
    }

    // Get the tags and / or categories for filtering from the request
    // filters are like: tag1,tag2|category1,category1 and each argument
    // is url encoded. 
    // If 'all' is passed as argument value, everything is fetched
    protected function getRequestedFilters() {

        $selectedTags = array();
        $selectedCategories = array();
        $extraParams = explode('|', urldecode($this->extraParams));

        // Getting the tags from the params
        if (isset($extraParams[0])) {
            if ($extraParams[0] == 'all') {
                $selectedTags[] = null;
            } else {
                $tags = explode(',', $extraParams[0]);
                foreach ($tags as $tag) {
                    $selectedTags[] = $this->getDoctrine()->getRepository('TagBundle:Tag')->findOneByTitle(urldecode($tag));
                }
            }
        } else {
            $selectedTags[] = null;
        }

        // Getting the categories from the params
        if (isset($extraParams[1])) {
            if ($extraParams[1] == 'all') {
                $selectedCategories[] = null;
            } else {
                $categories = explode(',', $extraParams[1]);
                foreach ($categories as $category) {
                    $selectedCategories[] = $this->getDoctrine()->getRepository('CategoryBundle:Category')->findOneByTitle(urldecode($category));
                }
            }
        } else {
            $selectedCategories[] = null;
        }

        // Set the tags and category objects to properly use the filters
        $filterParams = array('tags' => new \Doctrine\Common\Collections\ArrayCollection($selectedTags), 'categories' => new \Doctrine\Common\Collections\ArrayCollection($selectedCategories));

        return $filterParams;
    }

    // Get the ids of the filter categories
    protected function getCategoryFilterIds($selectedCategoriesArray) {

        $categoryIds = array();

        if (empty($selectedCategoriesArray[0])) {
            $selectedCategoriesArray = $this->getDoctrine()->getRepository('CategoryBundle:Category')->findAll();
        }

        foreach ($selectedCategoriesArray as $selectedCategoriesEntity) {
            $categoryIds[] = $selectedCategoriesEntity->getId();
        }

        return $categoryIds;
    }

    // Get the ids of the filter tags
    protected function getTagFilterIds($selectedTagsArray) {

        $tagIds = array();

        if (empty($selectedTagsArray[0])) {
            $selectedTagsArray = $this->getDoctrine()->getRepository('TagBundle:Tag')->findAll();
        }

        foreach ($selectedTagsArray as $selectedTagEntity) {
            $tagIds[] = $selectedTagEntity->getId();
        }

        return $tagIds;
    }

    // Get the titles of the filter categories
    protected function getCategoryFilterTitles($selectedCategoriesArray) {

        $categories = array();

        if (!empty($selectedCategoriesArray)) {
            foreach ($selectedCategoriesArray as $selectedCategoriesEntity) {
                $categories[] = $selectedCategoriesEntity->getTitle();
            }
        }

        $filterCategories = implode(',', $categories);

        if (empty($filterCategories)) {
            $filterCategories = 'all';
        }

        return $filterCategories;
    }

    // Get the titles of the filter tags
    protected function getTagFilterTitles($selectedTagsArray) {
        $tags = array();

        if (!empty($selectedTagsArray)) {
            foreach ($selectedTagsArray as $selectedTagEntity) {
                $tags[] = $selectedTagEntity->getTitle();
            }
        }

        $filterTags = implode(',', $tags);

        if (empty($filterTags)) {
            $filterTags = 'all';
        }

        return $filterTags;
    }

    // Sort homepage items by the pageOrder value of the objects returned after the merge
    protected function sortHomepageItemsCompare($introItemA, $introItemB) {
        if ($introItemA->getPageOrder() == $introItemB->getPageOrder()) {
            return 0;
        }
        return ($introItemA->getPageOrder() < $introItemB->getPageOrder()) ? -1 : 1;
    }

}
