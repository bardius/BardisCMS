<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use BardisCMS\PageBundle\Form\EventListener\SanitizeFieldSubscriber;

class ContactFormType extends AbstractType {

    // Creating the contact form and the fields
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('firstname', 'text', array(
            'label' => 'First Name',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter your First Name',
                'maxLength' => 50
            ))
        );

        $builder->add('surname', 'text', array(
            'label' => 'Surname',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter your Surname',
                'maxLength' => 50
            ))
        );

        $builder->add('email', 'email', array(
            'label' => 'Email',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter a valid email address',
                'maxLength' => 150
            ))
        );

        $builder->add('comment', 'textarea', array(
            'label' => 'Comment / Question',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter your Comment / Question',
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
        $collectionConstraint = new Assert\Collection(array(
            'firstname' => array(
                new Assert\NotBlank(array('message' => 'First Name should not be blank.')),
                new Assert\Regex(array('pattern' => '/[0-9]/', 'match' => false, 'message' => 'First Name cannot contain numbers')),
                new Assert\Length(array('min' => 2, 'max' => 50))
            ),
            'surname' => array(
                new Assert\NotBlank(array('message' => 'Surname should not be blank.')),
                new Assert\Regex(array('pattern' => '/[0-9]/', 'match' => false, 'message' => 'Surname cannot contain numbers')),
                new Assert\Length(array('min' => 2, 'max' => 50))
            ),
            'email' => array(
                new Assert\NotBlank(array('message' => 'Email should not be blank.')),
                new Assert\Length(array('min' => 2, 'max' => 150)),
                new Assert\Email(array('message' => 'Invalid email address.'))
            ),
            'comment' => array(
                new Assert\NotBlank(array('message' => 'Comment should not be blank.')),
                new Assert\Length(array('min' => 2, 'max' => 1000))
            ),
            'bottrap' => array(
                new Assert\Blank(array('message' => 'Bot Trap should be blank.')),
                new Assert\Length(array('max' => 1))
            )
        ));

        $resolver->setDefaults(array(
            'constraints' => $collectionConstraint
        ));
    }

    public function getName() {

        // Define the name of the form to call it for rendering
        return 'contactform';
    }

}
