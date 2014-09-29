<?php

/*
 * Comment Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\CommentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use BardisCMS\CommentBundle\Form\EventListener\SanitizeFieldSubscriber;

class CommentType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('title', 'text', array(
            'label' => 'Comment Title',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter Title',
                'maxLength' => 150
            ))
        );

        $builder->add('username', 'text', array(
            'label' => 'Your Name',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter Name',
                'maxLength' => 150
            ))
        );

        $builder->add('comment', 'textarea', array(
            'label' => 'Comment',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter your Comment',
                'maxLength' => 1000,
                'cols' => 70,
                'rows' => 8,
            ))
        );

        $builder->add('bottrap', 'text', array(
            'label' => 'Bot trap',
            'required' => false,
            'attr' => array(
                'placeholder' => '',
                'maxLength' => 1
            ))
        );

        // Sanitize data to avoid XSS attacks 
        $builder->addEventSubscriber(new SanitizeFieldSubscriber());
    }

    // Adding field validation constraints
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'intention' => 'comment_form',
            'data_class' => 'BardisCMS\CommentBundle\Entity\Comment'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'add_comment_form';
    }

}
