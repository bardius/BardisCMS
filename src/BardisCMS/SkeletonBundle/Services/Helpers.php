<?php

/*
 * Skeleton Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\SkeletonBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

class Helpers {

    private $em;
    private $conn;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->conn = $em->getConnection();
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
}
