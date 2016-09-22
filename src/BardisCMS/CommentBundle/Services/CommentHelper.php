<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\CommentBundle\Services;

use Doctrine\ORM\EntityManager as EntityManager;

class CommentHelper
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    // Get the approved comments for the blog post
    public function getBlogPostComments($associated_object_id)
    {
        $comments = $this->em->getRepository('CommentBundle:Comment')->getCommentsForBlogPost($associated_object_id);

        return $comments;
    }
}
