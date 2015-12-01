<?php

/*
 * Comment Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\CommentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BardisCMS\CommentBundle\Form\EventListener\SanitizeFieldSubscriber;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('title', TextType::class, array(
            'label' => 'Comment Title',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter Title',
                'maxLength' => 150
            ))
        );

        $builder->add('username', TextType::class, array(
            'label' => 'Your Name',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter Name',
                'maxLength' => 150
            ))
        );

        $builder->add('comment', TextareaType::class, array(
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

        $builder->add('bottrap', TextType::class, array(
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
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'intention' => 'comment_form',
            'data_class' => 'BardisCMS\CommentBundle\Entity\Comment'
        ));
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    // Define the name of the form to call it for rendering
    public function getBlockPrefix() {
        return 'add_comment_form';
    }
}
