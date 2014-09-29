<?php

/*
 * Comment Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\CommentBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BardisCMS\CommentBundle\Entity\Comment;

class CommentFixtures extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $comment1 = new Comment();
        $comment1->setTitle('Sample Comment 1');
        $comment1->setUsername('blogger1');
        $comment1->setComment('To make a long story short. You can\'t go wrong by choosing Symfony! And no one has ever been fired for using Symfony.');
        $comment1->setApproved(true);
        $comment1->setCreated(new \DateTime());
        $comment1->setCommentType('Blog');
        $comment1->setBlogPost($manager->merge($this->getReference('blog1')));
        $manager->persist($comment1);

        $comment2 = new Comment();
        $comment2->setTitle('Sample Comment 2');
        $comment2->setUsername('blogger2');
        $comment2->setComment('To make a long story short. You can\'t go wrong by choosing Symfony! And no one has ever been fired for using Symfony 2.');
        $comment2->setApproved(true);
        $comment2->setCreated(new \DateTime());
        $comment2->setCommentType('Blog');
        $comment2->setBlogPost($manager->merge($this->getReference('blog1')));
        $manager->persist($comment2);

        $comment3 = new Comment();
        $comment3->setTitle('Sample Comment 3');
        $comment3->setUsername('blogger3');
        $comment3->setComment('To make a long story short. You can\'t go wrong by choosing Symfony! And no one has ever been fired for using Symfony 3.');
        $comment3->setApproved(true);
        $comment3->setCreated(new \DateTime());
        $comment3->setCommentType('Blog');
        $comment3->setBlogPost($manager->merge($this->getReference('blog1')));
        $manager->persist($comment3);

        $manager->flush();
    }

    public function getOrder() {
        return 8;
    }

}
