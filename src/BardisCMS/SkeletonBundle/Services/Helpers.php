<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\SkeletonBundle\Services;

use BardisCMS\SkeletonBundle\Entity\Skeleton as Skeleton;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Doctrine\ORM\EntityManager;

class Helpers
{
    private $em;
    private $conn;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->conn = $em->getConnection();
    }

    // Get the tags and / or categories for filtering from the request
    // filters are like: tag1,tag2|category1,category1 and each argument
    // is url encoded.
    // If 'all' is passed as argument value, everything is fetched
    public function getRequestedFilters($params = null)
    {
        $selectedTags = array();
        $selectedCategories = array();
        $extraParams = explode('|', urldecode($params));

        // Getting the tags from the params
        if (isset($extraParams[0])) {
            if ($extraParams[0] === 'all') {
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
            if ($extraParams[1] === 'all') {
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
    public function getCategoryFilterIds($selectedCategoriesArray)
    {
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
    public function getTagFilterIds($selectedTagsArray)
    {
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
    public function getCategoryFilterTitles($selectedCategoriesArray)
    {
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
    public function getTagFilterTitles($selectedTagsArray)
    {
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
    public function getAllowedPublishStates($userHighestRole)
    {
        $publishStates = array();

        // Setting ROLE_ANONYMOUS role for brevity
        if ($userHighestRole === '') {
            $userHighestRole = 'ROLE_ANONYMOUS';
        }

        // Very basic ACL permission check
        switch ($userHighestRole) {
            case 'ROLE_ANONYMOUS':
                array_push(
                    $publishStates,
                    Skeleton::STATUS_PUBLISHED,
                    Skeleton::STATUS_NONAUTHONLY
                );
                break;
            case 'ROLE_USER':
                array_push(
                    $publishStates,
                    Skeleton::STATUS_PUBLISHED,
                    Skeleton::STATUS_AUTHONLY
                );
                break;
            case 'ROLE_SUPER_ADMIN':
                array_push(
                    $publishStates,
                    Skeleton::STATUS_PUBLISHED,
                    Skeleton::STATUS_PREVIEW,
                    Skeleton::STATUS_AUTHONLY
                );
                break;
            default:
                array_push(
                    $publishStates,
                    Skeleton::STATUS_PUBLISHED,
                    Skeleton::STATUS_NONAUTHONLY
                );
        }

        return $publishStates;
    }

    // Simple publishing ACL based on publish state and user Allowed Publish States
    public function isUserAccessAllowedByRole($publishState, $userAllowedPublishStates)
    {
        $accessAllowedForUserRole = false;

        if (in_array($publishState, $userAllowedPublishStates, true)) {
            $accessAllowedForUserRole = true;
        }

        return $accessAllowedForUserRole;
    }
}
