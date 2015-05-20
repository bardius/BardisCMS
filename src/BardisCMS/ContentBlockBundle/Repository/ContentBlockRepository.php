<?php

/*
 * ContentBlock Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\ContentBlockBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ContentBlockRepository extends EntityRepository {

    // Function to retrieve the content blocks that are globally available
    public function getGlobalBlocks() {

        // Initalize the query builder variables
        $qb = $this->_em->createQueryBuilder();
        $availability = 'global';

        // The query to get all global content blocks
        $qb->select('DISTINCT b')
                ->from('ContentBlockBundle:ContentBlock', 'b')
                ->where(
                        $qb->expr()->eq('b.availability', ':availability')
                )
                ->orderBy('b.title', 'ASC')
                ->setParameter('availability', $availability)
        ;

        // Get the results
        $globalContentBlocks = $qb->getQuery()->getResult();

        return $globalContentBlocks;
    }

}
