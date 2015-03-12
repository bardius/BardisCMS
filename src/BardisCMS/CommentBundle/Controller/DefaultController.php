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
use BardisCMS\CommentBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller {

    // Add a new comment
    public function addCommentAction($commentType, $page_id = null) {

        if ($commentType == 'Blog') {
            $blog_post = $this->getBlog($page_id);

            if (!$blog_post) {
                throw $this->createNotFoundException('Unable to find Blog post.');
            } else {
                return $this->createComment($commentType, $blog_post, $page_id);
            }
        } else {
            throw $this->createNotFoundException('Commenting is not available.');
        }
    }

    // Validate form and store data with proper associations
    protected function createComment($commentType, $associated_object, $associated_object_id) {

        // Create new comment object and associate with the desired object
        $comment = new Comment();
        if ($commentType == 'Blog') {
            $comment->setBlogPost($associated_object);
            $comment->setCommentType('Blog');
            $comment->setApproved(false);
        } else {
            throw $this->createNotFoundException('Commenting is not available.');
        }

        // get the request and check if it was ajax based
        $request = $this->getRequest();
        $ajaxForm = $request->get('isAjax');
        if (!isset($ajaxForm) || !$ajaxForm) {
            $ajaxForm = false;
        }

        // Bind the request to the comment form
        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);

        //Prepare the responce data
        $errorList = array();
        $successMsg = '';
        $formhasErrors = true;

        if ($form->isValid()) {

            // Persist (save) the form data to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            // The responce for the user upon successful submission
            $successMsg = 'Thank you submitting your comment.';
            $formMessage = $successMsg;
            $formhasErrors = false;
        } else {
            // Validate the data and get errors
            $errorList = $this->getFormErrorMessages($form);
            $formMessage = 'There was an error submitting your comment. Please try again.';
        }

        // Return the responce to the user
        if ($ajaxForm) {

            $ajaxFormData = array(
                'errors' => $errorList,
                'formMessage' => $formMessage,
                'hasErrors' => $formhasErrors
            );

            // Return the responce in json format
            $ajaxFormResponce = new Response(json_encode($ajaxFormData));
            $ajaxFormResponce->headers->set('Content-Type', 'application/json');

            return $ajaxFormResponce;
        } else {
            if ($commentType == 'Blog') {

                // Clear the form data object if it was submited successfully
                if (!$formhasErrors) {
                    $comment = new Comment();
                }

                // Retrieving the comments the view
                $postComments = $this->getBlogPostComments($associated_object_id);

                // get the blog post details (similar to the blog bundle)
                $settings = $this->get('bardiscms_settings.load_settings')->loadSettings();
                $page = $this->setBlogSettings($settings, $associated_object);

                // Return the responce as the blog post with form data
                return $this->render('BlogBundle:Default:page.html.twig', array('page' => $page, 'form' => $form->createView(), 'comments' => $postComments, 'comment' => $comment, 'ajaxform' => $ajaxForm, 'formMessage' => $formMessage));
            } else {
                throw $this->createNotFoundException('Commenting is not available.');
            }
        }
    }

    // Check if the associated Blog post exists
    protected function getBlog($blog_id) {

        $em = $this->getDoctrine()->getManager();
        $blog_post = $em->getRepository('BlogBundle:Blog')->find($blog_id);

        return $blog_post;
    }

    // Get the approved comments for the blog post
    protected function getBlogPostComments($blogPostId) {

        $comments = $this->getDoctrine()->getRepository('CommentBundle:Comment')->getCommentsForBlogPost($blogPostId);

        return $comments;
    }

    // Set the settings as defined from the service of the settings bundle
    // alternative could be to skip that bundle and use the config.yml
    protected function setBlogSettings($settings, $page) {
        if (is_object($settings)) {
            if ($settings->getUseWebsiteAuthor()) {
                $page->metaAuthor = $settings->getWebsiteAuthor();
            } else {
                $page->metaAuthor = $page->getAuthor()->getUsername();
            }

            $pageTitle = $page->getTitle();
            $titleKeywords = trim(preg_replace("/\b[A-za-z0-9']{1,3}\b/", "", strtolower($pageTitle)));
            $titleKeywords = str_replace(' ', ',', preg_replace('!\s+!', ' ', $titleKeywords));
            $fromTitle = $pageTitle . ' ' . $settings->getFromTitle();
            $pageTitle .= ' - ' . $settings->getWebsiteTitle();

            $page->pagetitle = $pageTitle;

            $page->enableGA = $settings->getEnableGoogleAnalytics();
            $page->gaID = $settings->getGoogleAnalyticsId();

            if ($page->getKeywords() === null) {
                $page->setKeywords($settings->getMetaKeywords() . ',' . $titleKeywords);
            } else {
                $page->setKeywords($page->getKeywords() . ',' . $titleKeywords);
            }

            if ($page->getDescription() === null) {
                $page->setDescription($settings->getMetaDescription() . ' ' . $fromTitle);
            } else {
                $page->setDescription($page->getDescription() . ' ' . $fromTitle);
            }
        } else {
            $page->metaAuthor = '';
            $pageTitle = $page->getTitle();
            $titleKeywords = trim(preg_replace("/\b[A-za-z0-9']{1,3}\b/", "", strtolower($pageTitle)));
            $titleKeywords = str_replace(' ', ',', preg_replace('!\s+!', ' ', $titleKeywords));
            $page->pagetitle = $pageTitle;
            $page->enableGA = false;
            $page->gaID = null;

            $page->setDescription($page->getDescription());
            $page->setKeywords($page->getKeywords() . ',' . $titleKeywords);
        }

        return $page;
    }

    // Get the error messages of the comment form assosiated with their fields in an array
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

}
