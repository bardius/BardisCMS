<?php

/*
 * Sonata User Bundle Overrides
 * This file is part of the BardisCMS.
 * Manage the extended Sonata User entity with extra information for the users
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\UserManager as BaseUserManager;
use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\DatagridBundle\Pager\Doctrine\Pager;
use Sonata\DatagridBundle\ProxyQuery\Doctrine\ProxyQuery;
use Sonata\UserBundle\Model\UserManagerInterface;

/**
 * Class UserManager.
 */
class UserManager extends BaseUserManager implements UserManagerInterface, ManagerInterface {

    /**
     * Function to retrieve the users with pagination
     *
     * @param int|null      $currentPage
     * @param int|null      $totalPageItems
     * @param string|null   $userSearchTerm
     * @param array|null    $currentUserId
     *
     * @return array[]
     */
    public function getAllUsersPaginated($currentPage = 0, $totalPageItems = 20, $userSearchTerm, $currentUserId = []) {

        $usernameList = null;

        // Initialize the query builder variables
        $qb = $this->repository->createQueryBuilder('u');
        $countqb = $this->repository->createQueryBuilder('u');

        // The query to get the page items for the current paginated listing page
        $qb->select('DISTINCT u')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('u.enabled', ':enabledState'),
                    $qb->expr()->eq('u.confirmed', ':confirmedState'),
                    $qb->expr()->eq('u.locked', ':lockedState'),
                    $qb->expr()->notIn('u.id', ':currentUserId'),
                    $qb->expr()->eq('u.isSystemUser', ':isSystemUser'),
                    $qb->expr()->like('u.username', ':userSearchTerm')
                )
            )
            ->orderBy('u.username', 'ASC')
            ->setParameter('enabledState', true)
            ->setParameter('confirmedState', true)
            ->setParameter('lockedState', false)
            ->setParameter('isSystemUser', false)
            ->setParameter('userSearchTerm', '%' . $userSearchTerm . '%')
            ->setParameter('currentUserId', $currentUserId)
        ;

        // The query to get the total users count
        $countqb->select('COUNT(DISTINCT u.id)')
            ->where(
                $countqb->expr()->andX(
                    $countqb->expr()->eq('u.enabled', ':enabledState'),
                    $qb->expr()->eq('u.confirmed', ':confirmedState'),
                    $countqb->expr()->eq('u.locked', ':lockedState'),
                    $countqb->expr()->notIn('u.id', ':currentUserId'),
                    $countqb->expr()->eq('u.isSystemUser', ':isSystemUser'),
                    $qb->expr()->like('u.username', ':userSearchTerm')
                )
            )
            ->orderBy('u.username', 'ASC')
            ->setParameter('enabledState', true)
            ->setParameter('confirmedState', true)
            ->setParameter('lockedState', false)
            ->setParameter('isSystemUser', false)
            ->setParameter('userSearchTerm', '%' . $userSearchTerm . '%')
            ->setParameter('currentUserId', $currentUserId)
        ;

        $totalResultsCount = intval($countqb->getQuery()->getSingleScalarResult());

        // Get the paginated results
        $usernameList = $this->getPaginatedResults($qb, $totalResultsCount, $currentPage, $totalPageItems);

        return $usernameList;
    }

    // Function to define what page of user results items will be returned for each paginated listing page
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
        if($totalPageItems > 0){
            $totalPages = ceil($totalResultsCount / $totalPageItems);
        }
        // Set the page items and pagination to be returned
        $pageList = array('pages' => $pages, 'totalPages' => $totalPages);

        return $pageList;
    }
}
