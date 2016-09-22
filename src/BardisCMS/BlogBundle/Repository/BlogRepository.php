<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BlogRepository extends EntityRepository
{
    // Function to retrieve the blog posts of a category with pagination
    public function getCategoryItems($categoryIds, $currentPageId, $publishStates, $currentpage, $totalpageitems)
    {
        $blogPostList = null;

        if (!empty($categoryIds)) {

            // Initalize the query builder variables
            $qb = $this->_em->createQueryBuilder();
            $countqb = $this->_em->createQueryBuilder();

            // The query to get the blog post items for the current paginated listing page
            $qb->select('DISTINCT p')
                    ->from('BlogBundle:Blog', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->where($qb->expr()->andX(
                                    $qb->expr()->in('c.id', ':category'), $qb->expr()->in('p.publishState', ':publishState'), $qb->expr()->neq('p.id', ':currentPage'), $qb->expr()->neq('p.pagetype', ':categorypagePageType')
                    ))
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('categorypagePageType', 'category_page')
                    ->setParameter('currentPage', $currentPageId)
            ;

            // The query to get the total blog post items count
            $countqb->select('COUNT(DISTINCT p.id)')
                    ->from('BlogBundle:Blog', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->where($countqb->expr()->andX(
                                    $countqb->expr()->in('c.id', ':category'), $countqb->expr()->in('p.publishState', ':publishState'), $countqb->expr()->neq('p.id', ':currentPage'), $countqb->expr()->neq('p.pagetype', ':categorypagePageType')
                    ))
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('categorypagePageType', 'category_page')
                    ->setParameter('currentPage', $currentPageId)
            ;

            $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

            // Get the paginated results
            $blogPostList = $this->getPaginatedResults($qb, $totalResultsCount, $currentpage, $totalpageitems);
        }

        return $blogPostList;
    }

    // Function to retrieve the blog posts of tag/category combination with pagination
    public function getTaggedCategoryItems($categoryIds, $currentPageId, $publishStates, $currentpage, $totalpageitems, $tagIds)
    {
        $blogPostList = null;

        if (!empty($categoryIds)) {
            // Initalize the query builder variables
            $qb = $this->_em->createQueryBuilder();
            $countqb = $this->_em->createQueryBuilder();

            // The query to get the blog post items for the current paginated listing page
            $qb->select('DISTINCT p')
                    ->from('BlogBundle:Blog', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->innerJoin('p.tags', 't')
                    ->where($qb->expr()->andX(
                                    $qb->expr()->in('c.id', ':category'), $qb->expr()->in('t.id', ':tag'), $qb->expr()->in('p.publishState', ':publishState'), $qb->expr()->neq('p.id', ':currentPage'), $qb->expr()->eq('p.pagetype', ':pagetype')
                    ))
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('pagetype', 'blog_article')
                    ->setParameter('currentPage', $currentPageId)
            ;

            // The query to get the total blog post items count
            $countqb->select('COUNT(DISTINCT p.id)')
                    ->from('BlogBundle:Blog', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->innerJoin('p.tags', 't')
                    ->where($countqb->expr()->andX(
                                    $countqb->expr()->in('c.id', ':category'), $countqb->expr()->in('t.id', ':tag'), $countqb->expr()->in('p.publishState', ':publishState'), $countqb->expr()->neq('p.id', ':currentPage'), $countqb->expr()->eq('p.pagetype', ':pagetype')
                    ))
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('pagetype', 'blog_article')
                    ->setParameter('currentPage', $currentPageId)
            ;

            $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

            // Get the paginated results
            $blogPostList = $this->getPaginatedResults($qb, $totalResultsCount, $currentpage, $totalpageitems);
        }

        return $blogPostList;
    }

    // Function to retrieve the blog posts of a tag with pagination
    public function getTaggedItems($tagIds, $currentPageId, $publishStates, $currentpage, $totalpageitems)
    {
        $blogPostList = null;

        if (!empty($tagIds)) {

            // Initalize the query builder variables
            $qb = $this->_em->createQueryBuilder();
            $countqb = $this->_em->createQueryBuilder();

            // The query to get the blog post items for the current paginated listing page
            $qb->select('DISTINCT p')
                    ->from('BlogBundle:Blog', 'p')
                    ->innerJoin('p.tags', 't')
                    ->where($qb->expr()->andX(
                                    $qb->expr()->in('t.id', ':tag'), $qb->expr()->in('p.publishState', ':publishState'), $qb->expr()->neq('p.id', ':currentPage'), $qb->expr()->eq('p.pagetype', ':pagetype')
                    ))
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('pagetype', 'blog_article')
                    ->setParameter('currentPage', $currentPageId)
            ;

            // The query to get the total blog post items count
            $countqb->select('COUNT(DISTINCT p.id)')
                    ->from('BlogBundle:Blog', 'p')
                    ->innerJoin('p.tags', 't')
                    ->where($countqb->expr()->andX(
                                    $countqb->expr()->in('t.id', ':tag'), $countqb->expr()->in('p.publishState', ':publishState'), $countqb->expr()->neq('p.id', ':currentPage'), $countqb->expr()->eq('p.pagetype', ':pagetype')
                    ))
                    ->orderBy('p.date', 'DESC')
                    ->setParameter('tag', $tagIds)
                    ->setParameter('publishState', $publishStates)
                    ->setParameter('pagetype', 'blog_article')
                    ->setParameter('currentPage', $currentPageId)
            ;

            $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

            // Get the paginated results
            $blogPostList = $this->getPaginatedResults($qb, $totalResultsCount, $currentpage, $totalpageitems);
        }

        return $blogPostList;
    }

    // Function to retrieve the blog posts of the homepage category
    public function getHomepageItems($categoryIds, $publishStates)
    {
        $blogPostList = null;

        if (!empty($categoryIds)) {

            // Initalize the query builder variables
            $qb = $this->_em->createQueryBuilder();

            // The query to get the blog post items for the homepage page
            $qb->select('DISTINCT p')
                    ->from('BlogBundle:Blog', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->where($qb->expr()->andX(
                                    $qb->expr()->in('c.id', ':category'), $qb->expr()->in('p.publishState', ':publishState')
                    ))
                    ->orderBy('p.pageOrder', 'ASC')
                    ->setParameter('category', $categoryIds)
                    ->setParameter('publishState', $publishStates);

            // Get the blog posts
            $blogPostList = $qb->getQuery()->getResult();
        }

        return $blogPostList;
    }

    // Function to retrieve the blog posts of the homepage category
    public function getFeaturedItems($categoryTitle, $publishStates, $maxResults)
    {
        $blogPostList = null;

        if (!empty($categoryTitle)) {

            // Initalize the query builder variables
            $qb = $this->_em->createQueryBuilder();

            // The query to get the blog post items for the homepage page
            $qb->select('DISTINCT p')
                    ->from('BlogBundle:Blog', 'p')
                    ->innerJoin('p.categories', 'c')
                    ->where($qb->expr()->andX(
                                    $qb->expr()->in('c.title', ':category'), $qb->expr()->in('p.publishState', ':publishState')
                    ))
                    ->orderBy('p.date', 'DESC')
                    ->setFirstResult(0)
                    ->setMaxResults($maxResults)
                    ->setParameter('category', $categoryTitle)
                    ->setParameter('publishState', $publishStates);

            // Get the blog posts
            $blogPostList = $qb->getQuery()->getResult();
        }

        return $blogPostList;
    }

    // Function to retrieve a blog post list for sitemap
    public function getSitemapList($publishStates)
    {

        // Initalize the query builder variables
        $qb = $this->_em->createQueryBuilder();

        // The query to get all blog post items
        $qb->select('DISTINCT p')
                ->from('BlogBundle:Blog', 'p')
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

    // Function to define what blog post items will be returned for each paginated listing page
    public function getPaginatedResults($qb, $totalResultsCount, $currentpage, $totalblogposts)
    {
        $blogPosts = null;
        $totalPages = 1;

        // Calculate and set the starting and last blog post item to retrieve
        if ((isset($currentpage)) && (isset($totalblogposts))) {
            if ($totalblogposts > 0) {
                $startingItem = (intval($currentpage) * $totalblogposts);
                $qb->setFirstResult($startingItem);
                $qb->setMaxResults($totalblogposts);
            }
        }

        // Get paginated results
        $blogPosts = $qb->getQuery()->getResult();
        // Get the total pagination pages
        if ($totalblogposts > 0) {
            $totalPages = ceil($totalResultsCount / $totalblogposts);
        }
        // Set the blog post items and pagination to be returned
        $blogPostList = array('pages' => $blogPosts, 'totalPages' => $totalPages);

        return $blogPostList;
    }

    // Function to get all the blog post items
    public function getAllItems($currentPageId, $publishStates, $currentpage, $totalblogposts)
    {

        // Initalize the query builder variables
        $qb = $this->_em->createQueryBuilder();
        $countqb = $this->_em->createQueryBuilder();

        // The query to get all blog post items
        $qb->select('DISTINCT p')
                ->from('BlogBundle:Blog', 'p')
                ->where($qb->expr()->andX(
                                $qb->expr()->in('p.publishState', ':publishState'), $qb->expr()->eq('p.pagetype', ':pagetype'), $qb->expr()->neq('p.id', ':currentPage')
                ))
                ->orderBy('p.date', 'DESC')
                ->setParameter('publishState', $publishStates)
                ->setParameter('pagetype', 'blog_article')
                ->setParameter('currentPage', $currentPageId)
        ;

        // The query to get the total blog post items count
        $countqb->select('COUNT(DISTINCT p.id)')
                ->from('BlogBundle:Blog', 'p')
                ->where($countqb->expr()->andX(
                                $countqb->expr()->in('p.publishState', ':publishState'), $countqb->expr()->eq('p.pagetype', ':pagetype'), $countqb->expr()->neq('p.id', ':currentPage')
                ))
                ->orderBy('p.date', 'DESC')
                ->setParameter('publishState', $publishStates)
                ->setParameter('pagetype', 'blog_article')
                ->setParameter('currentPage', $currentPageId)
        ;

        $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

        // Get paginated results
        $blogPostList = $this->getPaginatedResults($qb, $totalResultsCount, $currentpage, $totalblogposts);

        return $blogPostList;
    }
}
