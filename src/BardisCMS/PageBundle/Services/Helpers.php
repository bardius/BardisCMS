<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use BardisCMS\PageBundle\Entity\Page as Page;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Form\Form;

class Helpers {

    private $em;
    private $conn;
    private $container;

    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->em = $em;
        $this->conn = $em->getConnection();
        $this->container = $container;
    }

    // Get the error messages of the contact form associated with their fields in an array
    public function getFormErrorMessages(Form $form) {

        $errors = array();
        $formErrors = iterator_to_array($form->getErrors(false, true));

        foreach ($formErrors as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            if($error->getMessagePluralization() !== null) {
                $errors[$key] = $this->container->get('translator')->transChoice(
                    $error->getMessage(),
                    $error->getMessagePluralization(),
                    $error->getMessageParameters()
                );
            } else {
                $errors[$key] = $this->container->get('translator')->trans(
                    $error->getMessage()
                );
            }

            //$errors[$key] = $template;
            //$errors[$key] = $this->container->get('translator')->trans($template, array(), 'validators');
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
    public function getRequestedFilters($params = null) {
        $selectedTags = array();
        $selectedCategories = array();
        $extraParams = explode('|', urldecode($params));

        // Getting the tags from the params
        if (isset($extraParams[0])) {
            if ($extraParams[0] == 'all') {
                $selectedTags[] = null;
            } else {
                $tags = explode(',', $extraParams[0]);
                foreach ($tags as $tag) {
                    $selectedTags[] = $this->em->getRepository('TagBundle:Tag')->findOneByTitle(urldecode($tag));
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
                    $selectedCategories[] = $this->em->getRepository('CategoryBundle:Category')->findOneByTitle(urldecode($category));
                }
            }
        } else {
            $selectedCategories[] = null;
        }

        // Set the tags and category objects to properly use the filters
        $filterParams = array('tags' => new ArrayCollection($selectedTags), 'categories' => new ArrayCollection($selectedCategories));

        return $filterParams;
    }

    // Get the ids of the filter categories
    public function getCategoryFilterIds($selectedCategoriesArray) {

        $categoryIds = array();

        if (empty($selectedCategoriesArray[0])) {
            $selectedCategoriesArray = $this->em->getRepository('CategoryBundle:Category')->findAll();
        }

        foreach ($selectedCategoriesArray as $selectedCategoriesEntity) {
            $categoryIds[] = $selectedCategoriesEntity->getId();
        }

        return $categoryIds;
    }

    // Get the ids of the filter tags
    public function getTagFilterIds($selectedTagsArray) {

        $tagIds = array();

        if (empty($selectedTagsArray[0])) {
            $selectedTagsArray = $this->em->getRepository('TagBundle:Tag')->findAll();
        }

        foreach ($selectedTagsArray as $selectedTagEntity) {
            $tagIds[] = $selectedTagEntity->getId();
        }

        return $tagIds;
    }

    // Get the titles of the filter categories
    public function getCategoryFilterTitles($selectedCategoriesArray) {

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
    public function getTagFilterTitles($selectedTagsArray) {
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

    // Get the publishStates that are allowed for the user
    public function getAllowedPublishStates($userHighestRole) {

        $publishStates = array();

        // Setting ROLE_ANONYMOUS role for brevity
        if ($userHighestRole == "") {
            $userHighestRole = "ROLE_ANONYMOUS";
        }

        // Very basic ACL permission check
        switch ($userHighestRole) {
            case "ROLE_ANONYMOUS":
                array_push(
                    $publishStates,
                    Page::STATUS_PUBLISHED,
                    Page::STATUS_NONAUTHONLY
                );
                break;
            case "ROLE_USER":
                array_push(
                    $publishStates,
                    Page::STATUS_PUBLISHED,
                    Page::STATUS_AUTHONLY
                );
                break;
            case "ROLE_SUPER_ADMIN":
                array_push(
                    $publishStates,
                    Page::STATUS_PUBLISHED,
                    Page::STATUS_PREVIEW,
                    Page::STATUS_AUTHONLY
                );
                break;
            default:
                array_push(
                    $publishStates,
                    Page::STATUS_PUBLISHED,
                    Page::STATUS_NONAUTHONLY
                );
        }

        return $publishStates;
    }

    // Simple publishing ACL based on publish state and user Allowed Publish States
    public function isUserAccessAllowedByRole($publishState, $userAllowedPublishStates) {

        $accessAllowedForUserRole = false;

        if(in_array($publishState, $userAllowedPublishStates)){
            $accessAllowedForUserRole = true;
        }

        return $accessAllowedForUserRole;
    }
}
