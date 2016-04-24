<?php

/*
 * Comment Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\CommentBundle\Form\Handler;

use BardisCMS\CommentBundle\Entity\Comment;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager as EntityManager;

class CommentFormHandler
{
    protected $request;
    protected $form;
    private $container;

    /**
     * Construct handler for CommentFormHandler
     *
     * @param FormInterface $form
     * @param Request $request
     * @param Container $container
     * @param EntityManager $em
     *
     */
    public function __construct(FormInterface $form, Request $request, Container $container, EntityManager $em) {
        $this->form = $form;
        $this->request = $request;
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * Process the form for CommentForm
     *
     * @param Comment $comment
     *
     * @return boolean
     */
    public function process(Comment $comment)
    {
        $this->form->setData($comment);

        if ('POST' === $this->request->getMethod() && $this->request->request->has('commentform_form')) {

            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $processedComment = $this->form->getData();

                $this->onSuccess($processedComment);

                return true;
            }
        }

        return false;
    }

    /**
     * onSuccess after processing the form for CommentForm
     *
     * @param Comment $comment
     *
     */
    protected function onSuccess(Comment $comment)
    {
        // Persist (save) the form data to the database
        $this->em->persist($comment);
        $this->em->flush();
    }

    /**
     * Extend with a method that returns the errors of the process
     *
     * @return Array
     */
    public function getErrors()
    {
        return $this->container->get('bardiscms_page.services.helpers')->getFormErrorMessages($this->form);
    }
}
