<?php
/*
 * Skeleton Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */
namespace BardisCMS\SkeletonBundle\Controller;

use BardisCMS\SkeletonBundle\Entity\Skeleton;
use BardisCMS\PageBundle\Entity\Page;
use BardisCMS\SkeletonBundle\Form\FilterResultsForm;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    // Get the Skeleton page id based on alias from route
    public function aliasAction($alias, $extraParams = null, $currentpage = 0, $totalpageitems = 0) 
    {

        $page = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->findOneByAlias($alias);
        
        if (!$page) {
            return $this->render404Page();
        }
        
        $linkUrlParams = $extraParams;
		
        return $this->showPageAction($page->getId(), $extraParams, $currentpage, $totalpageitems, $linkUrlParams);
    }
    
    
    // Set the variables and render the view to display page
    public function showPageAction($id, $extraParams = null, $currentpage = null, $totalpageitems = null, $linkUrlParams = null)
    {   
        
        // Get data to display
        $page       = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->find($id);        
        $userRole   = $this->get('sonata_user.services.helpers')->getLoggedUserHighestRole();        
        $settings   = $this->get('bardiscms_settings.load_settings')->loadSettings();
        
        // Simple ACL for publishing
        if($page->getPublishState() == 0)
        {   
            return $this->render404Page();
        }
            
        if($page->getPublishState() == 2 && $userRole == '')
        {   
            return $this->render404Page();
        }
        
        if ($userRole == "")
        {
            $publishStates = array(1);
        }
        else
        {
            $publishStates = array(1, 2);                
        }
		
		//var_dump($this->container->getParameter('kernel.environment'));
		
		if($this->container->getParameter('kernel.environment') == 'prod' && $settings->getActivateHttpCache()){
			
			$response = new Response();
			
			// set a custom Cache-Control directive
			$response->headers->addCacheControlDirective('must-revalidate', true);
			// set multiple vary headers
			$response->setVary(array('Accept-Encoding', 'User-Agent'));
			// create a Response with a Last-Modified header
			$response->setLastModified($page->getDateLastModified());
			// Set response as public. Otherwise it will be private by default.
			$response->setPublic();
			
			//var_dump($response->isNotModified($this->getRequest()));
			//var_dump($response->getStatusCode());
			if (!$response->isNotModified($this->getRequest())) {
				// Marks the Response stale
				$response->expire();
			}
			else{				
				// return the 304 Response immediately
				$response->setSharedMaxAge(3600);
				return $response;
			}
		}
        
        // Set the website settings and metatags
		$page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($page);
        
        // Set the pagination variables        
        if(is_object($settings))
        {        
            if(!$totalpageitems)
            {
                $totalpageitems = $settings->getItemsPerPage();
            }               
        }
        else
        {
            $totalpageitems = 10;                 
        }
        
        // Render the correct view depending on pagetype
        return $this->renderPage($page, $id, $publishStates, $extraParams, $currentpage, $totalpageitems, $linkUrlParams);        
    }
    
    
    // Get the tags and / or categories for filtering from the request
    // filters are like: tag1,tag2|category1,category1 and each argument
    // is url encoded. If all is passed as argument value everything is fetched
    public function getRequestedFilters($extraParams)
    {
        
        $selectedTags       = array();
        $selectedCategories = array();        
        $extraParams        = explode('|', $extraParams);
        
        if (isset($extraParams[0]))
        {
            if($extraParams[0] == 'all')
            {
                $selectedTags[] = null;
            }
            else
            {
                $tags = explode(',', $extraParams[0]);
                foreach($tags as $tag)
                {
                    $selectedTags[] = $this->getDoctrine()->getRepository('TagBundle:Tag')->findOneByTitle(urldecode($tag));
                }
            }
        }
        else
        {
            $selectedTags[] = null;
        }
        
        if (isset($extraParams[1]))
        {
            if($extraParams[1] == 'all')
            {
                $selectedCategories[] = null;
            }
            else
            {
                $categories = explode(',', $extraParams[1]);
                foreach($categories as $category)
                {
                    $selectedCategories[] = $this->getDoctrine()->getRepository('PageBundle:Category')->findOneByTitle(urldecode($category));
                }
            }
        }
        else
        {
            $selectedCategories[] = null;
        }
        
        $filterParams = array('tags' => new \Doctrine\Common\Collections\ArrayCollection($selectedTags), 'categories' => new \Doctrine\Common\Collections\ArrayCollection($selectedCategories));
        
        return $filterParams;
    }
    
    
    // Get the ids of the filter categories
    public function getCategoryFilterIds($selectedCategoriesArray)
    {
        
        $categoryIds = array(); 
        
        if(empty($selectedCategoriesArray[0]))
        {
            $selectedCategoriesArray = $this->getDoctrine()->getRepository('PageBundle:Category')->findAll();
        }
        
        foreach($selectedCategoriesArray as $selectedCategoriesEntity)
        {
            $categoryIds[] = $selectedCategoriesEntity->getId();     
        }
        
        return $categoryIds;
    }
    
    
    // Get the ids of the filter tags
    public function getTagFilterIds($selectedTagsArray)
    {       
        
        $tagIds = array();      
        
        if(empty($selectedTagsArray[0]))
        {
            $selectedTagsArray = $this->getDoctrine()->getRepository('TagBundle:Tag')->findAll();
        }
        
        foreach($selectedTagsArray as $selectedTagEntity)
        {
            $tagIds[] = $selectedTagEntity->getId();     
        }
        
        return $tagIds;
    }
    
    
    // Get the required data to display to the correct view depending on pagetype
    public function renderPage($page, $id, $publishStates, $extraParams, $currentpage, $totalpageitems, $linkUrlParams){
		// Check if mobile content should be served		
        $serveMobile = $this->get('bardiscms_mobile_detect.device_detection')->testMobile();
		$settings = $this->get('bardiscms_settings.load_settings')->loadSettings();
                        
        if ($page->getPagetype() == 'category_page')
        {            
            $tagIds      = $this->getTagFilterIds($page->getTags()->toArray());           
            $categoryIds = $this->getCategoryFilterIds($page->getCategories()->toArray());
            
            if(!empty($tagIds))
            {                
                $pageList = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->getTaggedCategoryItems($categoryIds, $id, $publishStates, $currentpage, $totalpageitems, $tagIds);                
            }
            else
            {
                $pageList = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->getCategoryItems($categoryIds, $id, $publishStates, $currentpage, $totalpageitems);
            }
            
            $pages      = $pageList['pages'];
            $totalPages = $pageList['totalPages'];
            
            $response = $this->render('SkeletonBundle:Default:page.html.twig', array('page' => $page, 'pages' => $pages, 'totalPages' => $totalPages, 'extraParams' => $extraParams, 'currentpage' => $currentpage, 'linkUrlParams' => $linkUrlParams, 'totalpageitems' => $totalpageitems));
        }      
        else if ($page->getPagetype() == 'skeleton_filtered_list')
        {          
            $filterForm     = $this->createForm(new FilterResultsForm());                
            $filterData     = $this->getRequestedFilters($extraParams);
            $tagIds         = $this->getTagFilterIds($filterData['tags']->toArray());           
            $categoryIds    = $this->getCategoryFilterIds($filterData['categories']->toArray());
            
            $filterForm->setData($filterData); 
            
            if(!empty($categoryIds))
            {                
                $pageList = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->getTaggedCategoryItems($categoryIds, $id, $publishStates, $currentpage, $totalpageitems, $tagIds);                
            }
            else
            {            
                $pageList  = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->getTaggedItems($tagIds, $id, $publishStates, $currentpage, $totalpageitems);
            }
            
            $pages      = $pageList['pages'];
            $totalPages = $pageList['totalPages'];
            
            $response = $this->render('SkeletonBundle:Default:page.html.twig', array('page' => $page, 'pages' => $pages, 'totalPages' => $totalPages, 'extraParams' => $extraParams, 'currentpage' => $currentpage, 'linkUrlParams' => $linkUrlParams, 'totalpageitems' => $totalpageitems, 'filterForm' => $filterForm->createView()));
        }
        else if ($page->getPagetype() == 'skeleton_home')
        {            
            $pageList = $this->getDoctrine()->getRepository('SkeletonBundle:Skeleton')->getAllItems($id, $publishStates, $currentpage, $totalpageitems);
            
            $pages      = $pageList['pages'];
            $totalPages = $pageList['totalPages'];
            
            $response = $this->render('SkeletonBundle:Default:page.html.twig', array('page' => $page, 'pages' => $pages, 'totalPages' => $totalPages,  'extraParams' => $extraParams, 'currentpage' => $currentpage, 'linkUrlParams' => $linkUrlParams, 'totalpageitems' => $totalpageitems));
        }
        else{
			$response = $this->render('SkeletonBundle:Default:page.html.twig', array('page' => $page));			
		}
		
		if($this->container->getParameter('kernel.environment') == 'prod' && $settings->getActivateHttpCache()){	
			// set a custom Cache-Control directive
			$response->setPublic();
			$response->setLastModified($page->getDateLastModified());
			$response->setVary(array('Accept-Encoding', 'User-Agent'));
			$response->headers->addCacheControlDirective('must-revalidate', true);
			$response->setSharedMaxAge(3600);
		}
		
		return $response;
    }
    
    
    // Get and display to the 404 error page
    public function render404Page()
    {        
        $page       = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias('404');
		$settings = $this->get('bardiscms_settings.load_settings')->loadSettings();
        
        // Check if page exists
        if (!$page) {
            throw $this->createNotFoundException('No error page exists. No page found for with alias 404. Page has id: ' . $page->getId());
        }
        
        // Set the website settings and metatags
		$page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($page);
        
        $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $page))->setStatusCode(404);
		
		if($this->container->getParameter('kernel.environment') == 'prod' && $settings->getActivateHttpCache()){
			// set a custom Cache-Control directive
			$response->setPublic();
			$response->setLastModified($page->getDateLastModified());
			$response->setVary(array('Accept-Encoding', 'User-Agent'));
			$response->headers->addCacheControlDirective('must-revalidate', true);
			$response->setSharedMaxAge(3600);
		}
		
		return $response;
    }
    
    // Get and format the filtering arguments to use with the actions 
    public function filterPagesAction(Request $request) 
    {
        
        $filterTags         = 'all';
        $filterCategories   = 'all'; 
        $filterForm         = $this->createForm(new FilterResultsForm());
        $filterData         = null;
        
        if ($request->getMethod() == 'POST') {
            
            $filterForm->handleRequest($request);
            $filterData = $filterForm->getData();
            
            $filterTags         = $this->getTagFilterTitles($filterData['tags']);     
            $filterCategories   = $this->getCategoryFilterTitles($filterData['categories']);
        }
            
        $extraParams = urlencode($filterTags) . '|' . urlencode($filterCategories);
        
        $url = $this->get('router')->generate(
            'SkeletonBundle_tagged_full',
            array('extraParams' => $extraParams),
            true
        );
        return $this->redirect($url);
    }
    
    
    // Get the titles of the filter categories
    public function getCategoryFilterTitles($selectedCategoriesArray)
    {
        
        $categories = array(); 
        
        if(!empty($selectedCategoriesArray))
        {
            foreach($selectedCategoriesArray as $selectedCategoriesEntity)
            {
                $categories[] = $selectedCategoriesEntity->getTitle();     
            }
        }
        
        $filterCategories = implode(',', $categories);
        
        if(empty($filterCategories))
        {
            $filterCategories = 'all';
        }    
        
        return $filterCategories;
    }
    
    
    // Get the titles of the filter tags
    public function getTagFilterTitles($selectedTagsArray)
    {          
        $tags = array();
            
        if(!empty($selectedTagsArray))
        {
            foreach($selectedTagsArray as $selectedTagEntity)
            {
                $tags[] = $selectedTagEntity->getTitle();   
            }
        }
        
        $filterTags = implode(',', $tags);
        
        if(empty($filterTags))
        {
            $filterTags = 'all';
        }   
        
        return $filterTags;
    }
}