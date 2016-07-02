<?php

/*
 * Skeleton Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\SkeletonBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SkeletonRepository extends EntityRepository {

    // Function to retrieve the pages of a category with pagination
    public function getCategoryItems($categoryIds, $currentPageId, $publishStates, $currentpage, $totalpageitems) {

        $pageList = null;

        if (!empty($categoryIds)) {

             // Initialize the query builder variables
            $qb = $this->_em->createQueryBuilder();
            $countqb = $this->_em->createQueryBuilder();

            // The query to get the page items for the current paginated listing page
            $qb->select('DISTINCT p')
                    ->from('SkeletonBundle:Skeleton', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->where(
                        $qb->expr()->andX(
                            $qb->expr()->in('c.id', ':category'),
                            $qb->expr()->in('p.publishState', ':publishState'),
                            $qb->expr()->neq('p.id', ':currentPage'),
                            $qb->expr()->neq('p.pagetype', ':categorypagePageType')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('categorypagePageType', 'category_page')
                    ->setParameter('currentPage', $currentPageId)
            ;

            // The query to get the total page items count
            $countqb->select('COUNT(DISTINCT p.id)')
                    ->from('SkeletonBundle:Skeleton', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->where(
                        $countqb->expr()->andX(
                            $countqb->expr()->in('c.id', ':category'),
                            $countqb->expr()->in('p.publishState', ':publishState'),
                            $countqb->expr()->neq('p.id', ':currentPage'),
                            $countqb->expr()->neq('p.pagetype', ':categorypagePageType')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('categorypagePageType', 'category_page')
                    ->setParameter('currentPage', $currentPageId)
            ;

            $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

            // Get the paginated results
            $pageList = $this->getPaginatedResults($qb, $totalResultsCount, $currentpage, $totalpageitems);
        }

        return $pageList;
    }

    // Function to retrieve the pages of tag/category combination with pagination
    public function getTaggedCategoryItems($categoryIds, $currentPageId, $publishStates, $currentpage, $totalpageitems, $tagIds) {

        $pageList = null;

        if (!empty($categoryIds)) {
            // Initialize the query builder variables
            $qb = $this->_em->createQueryBuilder();
            $countqb = $this->_em->createQueryBuilder();

            // The query to get the page items for the current paginated listing page
            $qb->select('DISTINCT p')
                    ->from('SkeletonBundle:Skeleton', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->innerJoin('p.tags', 't')
                    ->where(
                        $qb->expr()->andX(
                            $qb->expr()->in('c.id', ':category'),
                            $qb->expr()->in('t.id', ':tag'),
                            $qb->expr()->in('p.publishState', ':publishState'),
                            $qb->expr()->neq('p.id', ':currentPage'),
                            $qb->expr()->eq('p.pagetype', ':pagetype')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('pagetype', 'skeleton_article')
                    ->setParameter('currentPage', $currentPageId)
            ;

            // The query to get the total page items count
            $countqb->select('COUNT(DISTINCT p.id)')
                    ->from('SkeletonBundle:Skeleton', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->innerJoin('p.tags', 't')
                    ->where(
                        $countqb->expr()->andX(
                            $countqb->expr()->in('c.id', ':category'),
                            $countqb->expr()->in('t.id', ':tag'),
                            $countqb->expr()->in('p.publishState', ':publishState'),
                            $countqb->expr()->neq('p.id', ':currentPage'),
                            $countqb->expr()->eq('p.pagetype', ':pagetype')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('pagetype', 'skeleton_article')
                    ->setParameter('currentPage', $currentPageId)
            ;

            $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

            // Get the paginated results
            $pageList = $this->getPaginatedResults($qb, $totalResultsCount, $currentpage, $totalpageitems);
        }

        return $pageList;
    }

    // Function to retrieve the pages of a tag with pagination
    public function getTaggedItems($tagIds, $currentPageId, $publishStates, $currentpage, $totalpageitems) {

        $pageList = null;

        if (!empty($tagIds)) {

            // Initialize the query builder variables
            $qb = $this->_em->createQueryBuilder();
            $countqb = $this->_em->createQueryBuilder();

            // The query to get the page items for the current paginated listing page
            $qb->select('DISTINCT p')
                    ->from('SkeletonBundle:Skeleton', 'p')
                    ->innerJoin('p.tags', 't')
                    ->where(
                        $qb->expr()->andX(
                            $qb->expr()->in('t.id', ':tag'),
                            $qb->expr()->in('p.publishState', ':publishState'),
                            $qb->expr()->neq('p.id', ':currentPage'),
                            $qb->expr()->eq('p.pagetype', ':pagetype')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('pagetype', 'skeleton_article')
                    ->setParameter('currentPage', $currentPageId)
            ;

            // The query to get the total page items count
            $countqb->select('COUNT(DISTINCT p.id)')
                    ->from('SkeletonBundle:Skeleton', 'p')
                    ->innerJoin('p.tags', 't')
                    ->where(
                        $countqb->expr()->andX(
                            $countqb->expr()->in('t.id', ':tag'),
                            $countqb->expr()->in('p.publishState', ':publishState'),
                            $countqb->expr()->neq('p.id', ':currentPage'),
                            $countqb->expr()->eq('p.pagetype', ':pagetype')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('pagetype', 'skeleton_article')
                    ->setParameter('currentPage', $currentPageId)
            ;

            $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

            // Get the paginated results
            $pageList = $this->getPaginatedResults($qb, $totalResultsCount, $currentpage, $totalpageitems);
        }

        return $pageList;
    }

    // Function to retrieve all the pages
    public function getAllItems($currentPageId, $publishStates, $currentpage, $totalpageitems) {

        $pageList = null;

        // Initialize the query builder variables
        $qb = $this->_em->createQueryBuilder();
        $countqb = $this->_em->createQueryBuilder();

        // The query to get the page items for the current paginated listing page
        $qb->select('p')
                ->from('SkeletonBundle:Skeleton', 'DISTINCT p')
                ->where(
                    $qb->expr()->andX(
                        $qb->expr()->in('p.publishState', ':publishState'),
                        $qb->expr()->eq('p.pagetype', ':pagetype'),
                        $qb->expr()->neq('p.id', ':currentPage')
                    )
                )
                ->orderBy('p.date', 'DESC')
                ->setParameter('publishState', $publishStates)
                ->setParameter('pagetype', 'skeleton_article')
                ->setParameter('currentPage', $currentPageId)
        ;

        // The query to get the total page items count
        $countqb->select('COUNT(DISTINCT p.id)')
                ->from('SkeletonBundle:Skeleton', 'p')
                ->where(
                    $countqb->expr()->andX(
                        $countqb->expr()->in('p.publishState', ':publishState'),
                        $countqb->expr()->eq('p.pagetype', ':pagetype'),
                        $countqb->expr()->neq('p.id', ':currentPage')
                    )
                )
                ->orderBy('p.date', 'DESC')
                ->setParameter('publishState', $publishStates)
                ->setParameter('pagetype', 'skeleton_article')
                ->setParameter('currentPage', $currentPageId)
        ;

        $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

        // Get the paginated results
        $pageList = $this->getPaginatedResults($qb, $totalResultsCount, $currentpage, $totalpageitems);

        return $pageList;
    }

    // Function to retrieve the pages of the homepage category
    public function getHomepageItems($categoryIds, $publishStates) {

        $pageList = null;

        if (!empty($categoryIds)) {
            // Initialize the query builder variables
            $qb = $this->_em->createQueryBuilder();

            // The query to get the page items for the homepage page
            $qb->select('DISTINCT p')
                    ->from('SkeletonBundle:Skeleton', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->where(
                        $qb->expr()->andX(
                            $qb->expr()->in('c.id', ':category'),
                            $qb->expr()->in('p.publishState', ':publishState')
                        )
                    )
                    ->orderBy('p.pageOrder', 'ASC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('publishState', $publishStates)
            ;

            // Get the results
            $pageList = $qb->getQuery()->getResult();
        }

        return $pageList;
    }

    // Function to retrieve a page list for sitemap
    public function getSitemapList($publishStates) {

        // Initialize the query builder variables
        $qb = $this->_em->createQueryBuilder();

        // The query to get all page items
        $qb->select('DISTINCT p')
                ->from('SkeletonBundle:Skeleton', 'p')
                ->where(
                    $qb->expr()->in('p.publishState', ':publishState')
                )
                ->orderBy('p.id', 'ASC')
                ->setParameter('publishState', $publishStates)
        ;

        // Get the results
        $sitemapList = $qb->getQuery()->getResult();

        return $sitemapList;
    }

    // Function to define what page items will be returned for each paginated listing page
    public function getPaginatedResults($qb, $totalResultsCount, $currentPage, $totalPageItems) {

        $pages = null;
        $totalPages = 1;

        // Calculate and set the starting and last page item to retrieve
        if ((isset($currentPage)) && (isset($totalPageItems))) {
            if ($totalPageItems > 0) {
                $startingItem = (intval($currentPage) * $totalPageItems);
                $qb->setFirstResult($startingItem);
                $qb->setMaxResults($totalPageItems);
            }
        }

        // Get paginated results
        $pages = $qb->getQuery()->getResult();
        // Get the total pagination pages
        $totalPages = ceil($totalResultsCount / $totalPageItems);
        // Set the page items and pagination to be returned
        $pageList = array('pages' => $pages, 'totalPages' => $totalPages);

        return $pageList;
    }
}
