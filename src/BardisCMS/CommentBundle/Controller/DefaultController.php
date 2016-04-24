<?php

/*
 * Comment Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\CommentBundle\Controller;

use BardisCMS\CommentBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use BardisCMS\PageBundle\Entity\Page as Page;
use BardisCMS\BlogBundle\Entity\Blog as Blog;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DefaultController extends Controller {

    // Adding variables required for the rendering of pages
    protected $container;
    private $pageRequest;
    private $userName;
    private $settings;
    private $serveMobile;
    private $enableHTTPCache;
    private $logged_user;
    private $isAjaxRequest;
    private $commentType;
    private $associated_object_id;
    private $associated_object;

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;

        // Setting the scoped variables required for the rendering of the page
        $this->userName = null;
        $this->commentType = null;
        $this->associated_object_id = null;
        $this->associated_object = null;

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
     * Add a new comment
     *
     * @param $commentType
     * @param $associated_object_id
     * @param $request
     *
     * @return Response
     */
    public function addCommentAction($commentType, $associated_object_id = null, Request $request) {
        $this->pageRequest = $request;
        $this->commentType = $commentType;
        $this->associated_object_id = $associated_object_id;

        if($this->associated_object_id == null){
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }

        switch($this->commentType){
            case 'Blog':
                $this->associated_object = $this->getBlogPost();

                if (!$this->associated_object) {
                    return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
                }

                // TODO: add ACL here

                return $this->createComment();
                break;

            default:
                return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }
    }

    /**
     * Validate form and store data with proper associations
     *
     * @return Response
     */
    protected function createComment() {
        $formMessage = null;
        $errorList = null;
        $formHasErrors = false;

        // Create new comment object and associate with the desired object
        $comment = $this->getInitialisedComment();

        // Bind the request to the comment form
        $form = $this->get('bardiscms_comment.comment.form');
        $formHandler = $this->get('bardiscms_comment.comment.form.handler');

        $process = $formHandler->process($comment);

        // Validate the data and get errors if any
        if ($process) {
            // Commend was saved, Clear the form data object
            $comment = new Comment();

            $formMessage = $this->container->get('translator')->trans('comment.form.response.success', array(), 'BardisCMSCommentBundle');
            $errorList = array();
            $formHasErrors = false;
        }
        else {
            $formMessage = $this->container->get('translator')->trans('comment.form.response.error', array(), 'BardisCMSCommentBundle');
            $errorList = $this->get('bardiscms_page.services.helpers')->getFormErrorMessages($form);
            $formHasErrors = true;
        }

        // If the request was Ajax based
        if($this->isAjaxRequest){
            if ($process) {
                return $this->onAjaxSuccess($process);
            } else {
                return $this->onAjaxError($formHandler);
            }
        }

        switch($this->commentType){
            case 'Blog':
                // Retrieving the comments the view
                $postComments = $this->getBlogPostComments();

                // Return the response as the blog post with form data
                $response = $this->render('BlogBundle:Default:page.html.twig', array(
                    'page' => $this->associated_object,
                    'form' => $form->createView(),
                    'ajaxform' => $this->isAjaxRequest,
                    'comments' => $postComments,
                    'comment' => $comment,
                    'formMessage' => $formMessage,
                    'errorList' => $errorList,
                    'formHasErrors' => $formHasErrors,
                    'logged_username' => $this->userName,
                    'mobile' => $this->serveMobile
                ));

                if ($this->enableHTTPCache) {
                    $response = $this->get('bardiscms_page.services.http_cache_headers_handler')->setResponseCacheHeaders(
                        $response,
                        $this->associated_object->getDateLastModified(),
                        false,
                        3600
                    );
                }

                return $response;
                break;

            default:
                return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_405);
        }
    }

    /**
     * Prepare the comment based on comment type
     *
     * @return Comment
     */
    protected function getInitialisedComment() {
        $initialisedComment = new Comment();

        switch($this->commentType) {
            case 'Blog':
                $initialisedComment->setBlogPost($this->associated_object);
                $initialisedComment->setCommentType('Blog');
                $initialisedComment->setApproved(true);
                break;
            default:
        }

        return $initialisedComment;
    }

    /**
     * Check if the associated Blog post exists
     *
     * @return Blog
     */
    protected function getBlogPost() {
        $blogPost = $this->getDoctrine()->getRepository('BlogBundle:Blog')->find($this->associated_object_id);

        // Set the website settings and metatags
        $blogPost = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($blogPost);

        return $blogPost;
    }

    /**
     * Get the associated Blog post comments
     *
     * @return Array
     */
    protected function getBlogPostComments() {
        $comments = $this->getDoctrine()->getRepository('CommentBundle:Comment')->getCommentsForBlogPost($this->associated_object_id);

        return $comments;
    }

    /**
     * Get the requested Blog post comment
     *
     * @param $commentId
     *
     * @return Array
     */
    protected function getCommentById($commentId) {
        $comment = $this->getDoctrine()->getRepository('CommentBundle:Comment')->getCommentById($commentId);

        return $comment;
    }

    /**
     * Handle Ajax response with errors
     *
     * @param $formHandler
     *
     * @return Response
     */
    protected function onAjaxError($formHandler)
    {
        $errorList = $formHandler->getErrors();
        $formMessage = 'comment.form.response.error';
        $formHasErrors = true;
        $newComment = null;

        return $this->returnAjaxResponse($errorList, $formMessage, $formHasErrors, $newComment);
    }

    /**
     * Handle Ajax response with success
     *
     * @return Response
     */
    protected function onAjaxSuccess($newCommentId)
    {
        $errorList = array();
        $formMessage = 'comment.form.response.success';
        $formHasErrors = false;
        $newComment = $this->getCommentById($newCommentId);

        return $this->returnAjaxResponse($errorList, $formMessage, $formHasErrors, $newComment);
    }

    /**
     * Return Ajax response
     *
     * @param $errorList
     * @param $formMessage
     * @param $formHasErrors
     * @param $newComment
     *
     * @return Response
     */
    protected function returnAjaxResponse($errorList, $formMessage, $formHasErrors, $newComment) {
        $ajaxFormData = array(
            'errors' => $errorList,
            'formMessage' => $this->container->get('translator')->trans($formMessage, array(), 'BardisCMSCommentBundle'),
            'hasErrors' => $formHasErrors,
            'newComment' => $newComment
        );

        $ajaxFormResponse = new Response(json_encode($ajaxFormData));
        $ajaxFormResponse->headers->set('Content-Type', 'application/json');

        return $ajaxFormResponse;
    }
}
