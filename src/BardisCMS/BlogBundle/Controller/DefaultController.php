<?php
/*
 * Blog Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */
namespace BardisCMS\BlogBundle\Controller;

use BardisCMS\BlogBundle\Entity\Blog;
use BardisCMS\PageBundle\Entity\Page;
use BardisCMS\BlogBundle\Form\FilterBlogPostsForm;

use BardisCMS\CommentBundle\Entity\Comment;
use BardisCMS\CommentBundle\Form\CommentType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{    
    // Get the blog page id based on alias from route
    public function aliasAction($alias, $extraParams = null, $currentpage = 0, $totalpageitems = 0) 
    {

        $page = $this->getDoctrine()->getRepository('BlogBundle:Blog')->findOneByAlias($alias);
        
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
        $page       = $this->getDoctrine()->getRepository('BlogBundle:Blog')->find($id);        
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
        if(!$totalpageitems)
        {
            if(is_object($settings))
            {
                $totalpageitems = $settings->getBlogItemsPerPage();                
            }
            else
            {
                $totalpageitems = 10;                 
            }
        }
        
        // Render the correct view depending on pagetype
        return $this->renderPage($page, $id, $publishStates, $extraParams, $currentpage, $totalpageitems, $linkUrlParams);     
    }    
	
    
    // Get the tags and / or categories for filtering from the request
    // filters are like: tag1,tag2|category1,category1 and each argument
    // is url encoded. If all is passed as argument value everything is fetched
    protected function getRequestedFilters($extraParams)
    {
        
        $selectedTags       = array();
        $selectedCategories = array();        
        $extraParams        = explode('|', urldecode($extraParams));
        
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
                    $selectedCategories[] = $this->getDoctrine()->getRepository('CategoryBundle:Category')->findOneByTitle(urldecode($category));
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
    protected function getCategoryFilterIds($selectedCategoriesArray)
    {
        
        $categoryIds = array(); 
        
        if(empty($selectedCategoriesArray[0]))
        {
            $selectedCategoriesArray = $this->getDoctrine()->getRepository('CategoryBundle:Category')->findAll();
        }
        
        foreach($selectedCategoriesArray as $selectedCategoriesEntity)
        {
            $categoryIds[] = $selectedCategoriesEntity->getId();     
        }
        
        return $categoryIds;
    }
    
    
    // Get the ids of the filter tags
    protected function getTagFilterIds($selectedTagsArray)
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
    protected function renderPage($page, $id, $publishStates, $extraParams, $currentpage, $totalpageitems, $linkUrlParams)
	{
		// Check if mobile content should be served		
        $serveMobile = $this->get('bardiscms_mobile_detect.device_detection')->testMobile();
		$settings = $this->get('bardiscms_settings.load_settings')->loadSettings();
                        
        if ($page->getPagetype() == 'blog_cat_page')
        {            
            $tagIds      = $this->getTagFilterIds($page->getTags()->toArray());           
            $categoryIds = $this->getCategoryFilterIds($page->getCategories()->toArray());
            
            if(!empty($tagIds))
            {                
                $pageList = $this->getDoctrine()->getRepository('BlogBundle:Blog')->getTaggedCategoryItems($categoryIds, $id, $publishStates, $currentpage, $totalpageitems, $tagIds);                
            }
            else
            {
                $pageList = $this->getDoctrine()->getRepository('BlogBundle:Blog')->getCategoryItems($categoryIds, $id, $publishStates, $currentpage, $totalpageitems);
            }
            
            $pages      = $pageList['pages'];
            $totalPages = $pageList['totalPages'];
            
            $response = $this->render('BlogBundle:Default:page.html.twig', array('page' => $page, 'pages' => $pages, 'totalPages' => $totalPages, 'extraParams' => $extraParams, 'currentpage' => $currentpage, 'linkUrlParams' => $linkUrlParams, 'totalpageitems' => $totalpageitems));
        }      
        else if ($page->getPagetype() == 'blog_filtered_list')
        {          
            $filterForm     = $this->createForm('filterblogpostsform');                
            $filterData     = $this->getRequestedFilters($extraParams);
            $tagIds         = $this->getTagFilterIds($filterData['tags']->toArray());           
            $categoryIds    = $this->getCategoryFilterIds($filterData['categories']->toArray());
            
            $filterForm->setData($filterData); 
            
            if(!empty($categoryIds))
            {                
                $pageList = $this->getDoctrine()->getRepository('BlogBundle:Blog')->getTaggedCategoryItems($categoryIds, $id, $publishStates, $currentpage, $totalpageitems, $tagIds);                
            }
            else
            {            
                $pageList  = $this->getDoctrine()->getRepository('BlogBundle:Blog')->getTaggedItems($tagIds, $id, $publishStates, $currentpage, $totalpageitems);
            }
            
            $pages      = $pageList['pages'];
            $totalPages = $pageList['totalPages'];
            
            $response = $this->render('BlogBundle:Default:page.html.twig', array('page' => $page, 'pages' => $pages, 'totalPages' => $totalPages, 'extraParams' => $extraParams, 'currentpage' => $currentpage, 'linkUrlParams' => $linkUrlParams, 'totalpageitems' => $totalpageitems, 'filterForm' => $filterForm->createView()));
        }
        else if ($page->getPagetype() == 'blog_home')
        {            
            $pageList = $this->getDoctrine()->getRepository('BlogBundle:Blog')->getAllItems($id, $publishStates, $currentpage, $totalpageitems);
            
            $pages      = $pageList['pages'];
            $totalPages = $pageList['totalPages'];
            
            $response = $this->render('BlogBundle:Default:page.html.twig', array('page' => $page, 'pages' => $pages, 'totalPages' => $totalPages,  'extraParams' => $extraParams, 'currentpage' => $currentpage, 'linkUrlParams' => $linkUrlParams, 'totalpageitems' => $totalpageitems));
        }
		else{		
			$commentsEnabled = true;

			if($commentsEnabled){
				// Retrieving the comments the views
				$postComments = $this->getPostComments($id);

				// Adding the form for new comment
				$comment = new Comment();
				$comment->setBlogPost($page);
				$form = $this->createForm(new CommentType(), $comment);

				$response = $this->render('BlogBundle:Default:page.html.twig', array('page' => $page, 'form' => $form->createView(), 'comments' => $postComments));			
			}
			else{		
				$response = $this->render('BlogBundle:Default:page.html.twig', array('page' => $page));			
			}
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
    protected function render404Page()
    {        
        $page  = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias('404');
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
    public function filterBlogPostsAction(Request $request) 
    {
        
        $filterTags         = 'all';
        $filterCategories   = 'all'; 
        $filterForm			= $this->createForm('filterblogpostsform'); 
        $filterData         = null;
        
        if ($request->getMethod() == 'POST') {
            
            $filterForm->handleRequest($request);
            $filterData = $filterForm->getData();
            
            $filterTags         = $this->getTagFilterTitles($filterData['tags']);     
            $filterCategories   = $this->getCategoryFilterTitles($filterData['categories']);
        }
            
        $extraParams = urlencode($filterTags) . '|' . urlencode($filterCategories);
        
        $url = $this->get('router')->generate(
            'BlogBundle_tagged_full',
            array('extraParams' => $extraParams),
            true
        );
        return $this->redirect($url);
    }
    
    
    // Get the titles of the filter categories
    protected function getCategoryFilterTitles($selectedCategoriesArray)
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
    protected function getTagFilterTitles($selectedTagsArray)
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
    
    // Get the approved comments for the blog post
    protected function getPostComments($blogPostId){
		
		$comments = null;
		$comments = $this->getDoctrine()->getRepository('CommentBundle:Comment')->getCommentsForBlogPost($blogPostId);
        
        return $comments;
    }
}
