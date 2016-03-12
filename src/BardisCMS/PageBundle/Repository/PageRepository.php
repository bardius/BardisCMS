<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository {

    // Function to retrieve the pages of a category with pagination
    public function getCategoryItems($categoryIds, $currentPageId, $publishStates, $currentPage, $totalPageItems) {

        $pageList = null;

        if (!empty($categoryIds)) {

            // Initialize the query builder variables
            $qb = $this->_em->createQueryBuilder();
            $countqb = $this->_em->createQueryBuilder();

            // The query to get the page items for the current paginated listing page
            $qb->select('DISTINCT p')
                    ->from('PageBundle:Page', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->where(
                        $qb->expr()->andX(
                            $qb->expr()->in('c.id', ':category'),
                            $qb->expr()->in('p.publishState', ':publishState'),
                            $qb->expr()->neq('p.id', ':currentPage'),
                            $qb->expr()->neq('p.pagetype', ':categoryPagePageType'),
                            $qb->expr()->neq('p.pagetype', ':homepagePageType')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('categoryPagePageType', 'category_page')
                    ->setParameter('currentPage', $currentPageId)
                    ->setParameter('homepagePageType', 'homepage')
            ;

            // The query to get the total page items count
            $countqb->select('COUNT(DISTINCT p.id)')
                    ->from('PageBundle:Page', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->where(
                        $countqb->expr()->andX(
                            $countqb->expr()->in('c.id', ':category'),
                            $countqb->expr()->in('p.publishState', ':publishState'),
                            $countqb->expr()->neq('p.id', ':currentPage'),
                            $countqb->expr()->neq('p.pagetype', ':categoryPagePageType'),
                            $countqb->expr()->neq('p.pagetype', ':homepagePageType')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('categoryPagePageType', 'category_page')
                    ->setParameter('currentPage', $currentPageId)
                    ->setParameter('homepagePageType', 'homepage')
            ;

            $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

            // Get the paginated results
            $pageList = $this->getPaginatedResults($qb, $totalResultsCount, $currentPage, $totalPageItems);
        }

        return $pageList;
    }

    // Function to retrieve the pages of tag/category combination with pagination
    public function getTaggedCategoryItems($categoryIds, $currentPageId, $publishStates, $currentPage, $totalPageItems, $tagIds) {

        $pageList = null;

        if (!empty($categoryIds)) {
            // Initialize the query builder variables
            $qb = $this->_em->createQueryBuilder();
            $countqb = $this->_em->createQueryBuilder();

            // The query to get the page items for the current paginated listing page
            $qb->select('DISTINCT p')
                    ->from('PageBundle:Page', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->innerJoin('p.tags', 't')
                    ->where(
                        $qb->expr()->andX(
                            $qb->expr()->in('c.id', ':category'),
                            $qb->expr()->in('t.id', ':tag'),
                            $qb->expr()->in('p.publishState', ':publishState'),
                            $qb->expr()->neq('p.id', ':currentPage'),
                            $qb->expr()->neq('p.pagetype', ':categoryPagePageType'),
                            $qb->expr()->neq('p.pagetype', ':homepagePageType')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('categoryPagePageType', 'category_page')
                    ->setParameter('currentPage', $currentPageId)
                    ->setParameter('homepagePageType', 'homepage')
            ;

            // The query to get the total page items count
            $countqb->select('COUNT(DISTINCT p.id)')
                    ->from('PageBundle:Page', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->innerJoin('p.tags', 't')
                    ->where(
                        $countqb->expr()->andX(
                            $countqb->expr()->in('c.id', ':category'),
                            $countqb->expr()->in('t.id', ':tag'),
                            $countqb->expr()->in('p.publishState', ':publishState'),
                            $countqb->expr()->neq('p.id', ':currentPage'),
                            $countqb->expr()->neq('p.pagetype', ':homepagePageType'),
                            $countqb->expr()->neq('p.pagetype', ':categoryPagePageType')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('categoryPagePageType', 'category_page')
                    ->setParameter('currentPage', $currentPageId)
                    ->setParameter('homepagePageType', 'homepage')
            ;

            $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

            // Get the paginated results
            $pageList = $this->getPaginatedResults($qb, $totalResultsCount, $currentPage, $totalPageItems);
        }

        return $pageList;
    }

    // Function to retrieve the pages of a tag with pagination
    public function getTaggedItems($tagIds, $currentPageId, $publishStates, $currentPage, $totalPageItems) {

        $pageList = null;

        if (!empty($tagIds)) {

            // Initialize the query builder variables
            $qb = $this->_em->createQueryBuilder();
            $countqb = $this->_em->createQueryBuilder();

            // The query to get the page items for the current paginated listing page
            $qb->select('DISTINCT p')
                    ->from('PageBundle:Page', 'p')
                    ->innerJoin('p.tags', 't')
                    ->where(
                        $qb->expr()->andX(
                            $qb->expr()->in('t.id', ':tag'),
                            $qb->expr()->in('p.publishState', ':publishState'),
                            $qb->expr()->neq('p.id', ':currentPage'),
                            $qb->expr()->neq('p.pagetype', ':categoryPagePageType'),
                            $qb->expr()->neq('p.pagetype', ':homepagePageType')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('categoryPagePageType', 'category_page')
                    ->setParameter('currentPage', $currentPageId)
                    ->setParameter('homepagePageType', 'homepage')
            ;

            // The query to get the total page items count
            $countqb->select('COUNT(DISTINCT p.id)')
                    ->from('PageBundle:Page', 'p')
                    ->innerJoin('p.tags', 't')
                    ->where(
                        $countqb->expr()->andX(
                            $countqb->expr()->in('t.id', ':tag'),
                            $countqb->expr()->in('p.publishState', ':publishState'),
                            $countqb->expr()->neq('p.id', ':currentPage'),
                            $countqb->expr()->neq('p.pagetype', ':categoryPagePageType'),
                            $countqb->expr()->neq('p.pagetype', ':homepagePageType')
                        )
                    )
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('categoryPagePageType', 'category_page')
                    ->setParameter('currentPage', $currentPageId)
                    ->setParameter('homepagePageType', 'homepage')
            ;

            $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

            // Get the paginated results
            $pageList = $this->getPaginatedResults($qb, $totalResultsCount, $currentPage, $totalPageItems);
        }

        return $pageList;
    }

    // Function to retrieve the pages of the homepage category
    public function getHomepageItems($categoryIds, $currentPageId, $publishStates) {

        $pageList = null;

        if (!empty($categoryIds)) {

            // Initialize the query builder variables
            $qb = $this->_em->createQueryBuilder();

            // The query to get the page items for the homepage page
            $qb->select('DISTINCT p')
                    ->from('PageBundle:Page', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->where(
                        $qb->expr()->andX(
                            $qb->expr()->in('c.id', ':category'),
                            $qb->expr()->in('p.publishState', ':publishState'),
                            $qb->expr()->neq('p.id', ':currentPage')
                        )
                    )
                    ->orderBy('p.pageOrder', 'ASC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('currentPage', $currentPageId)
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
            ->from('PageBundle:Page', 'p')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->in('p.publishState', ':publishState'),
                    $qb->expr()->neq('p.pagetype', ':systemPageType')
                )
            )
            ->orderBy('p.id', 'ASC')
            ->setParameter('publishState', $publishStates)
            ->setParameter('systemPageType', "system_page")
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
