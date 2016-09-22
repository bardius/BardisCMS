<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\CommentBundle\Form\Handler;

use BardisCMS\CommentBundle\Entity\Comment;
use Doctrine\ORM\EntityManager as EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentFormHandler
{
    protected $request;
    protected $form;
    private $container;

    /**
     * Construct handler for CommentFormHandler.
     *
     * @param FormInterface $form
     * @param Request       $request
     * @param Container     $container
     * @param EntityManager $em
     */
    public function __construct(FormInterface $form, Request $request, Container $container, EntityManager $em)
    {
        $this->form = $form;
        $this->request = $request;
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * Process the form for CommentForm.
     *
     * @param Comment $comment
     *
     * @return int
     */
    public function process(Comment $comment)
    {
        $this->form->setData($comment);

        if ('POST' === $this->request->getMethod() && $this->request->request->has('commentform_form')) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $processedComment = $this->form->getData();

                return $this->onSuccess($processedComment);
            }
        }

        return 0;
    }

    /**
     * onSuccess after processing the form for CommentForm.
     *
     * @param Comment $comment
     *
     * @return int
     */
    protected function onSuccess(Comment $comment)
    {
        // Persist (save) the form data to the database
        $this->em->persist($comment);
        $this->em->flush();

        return $comment->getId();
    }

    /**
     * Extend with a method that returns the errors of the process.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->container->get('bardiscms_page.services.helpers')->getFormErrorMessages($this->form);
    }
}
