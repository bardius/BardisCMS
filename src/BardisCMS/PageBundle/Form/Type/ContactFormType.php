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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Validator\Constraints as Assert;
use BardisCMS\PageBundle\Form\EventListener\SanitizeFieldSubscriber;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ContactFormType extends AbstractType {

    /**
     * Construct form for ContactFormType
     *
     */
    public function __construct() {
    }

    // Creating the contact form and the fields
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('firstname', TextType::class, array(
            'label' => 'contact_form.firstname',
            'translation_domain' => 'BardisCMSPageBundle',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter your First Name',
                'maxLength' => 50
            ))
        );

        $builder->add('surname', TextType::class, array(
            'label' => 'contact_form.lastname',
            'translation_domain' => 'BardisCMSPageBundle',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter your Surname',
                'maxLength' => 50
            ))
        );

        $builder->add('email', EmailType::class, array(
            'label' => 'contact_form.email',
            'translation_domain' => 'BardisCMSPageBundle',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter a valid email address',
                'maxLength' => 150
            ))
        );

        $builder->add('comment', TextareaType::class, array(
            'label' => 'contact_form.comment',
            'translation_domain' => 'BardisCMSPageBundle',
            'required' => true,
            'attr' => array(
                'placeholder' => '',
                'title' => 'Please enter your Comment / Question',
                'maxLength' => 1000,
                'cols' => 70,
                'rows' => 8,
            ))
        );

        $builder->add('bottrap', TextType::class, array(
            'label' => 'contact_form.bottrap',
            'translation_domain' => 'BardisCMSPageBundle',
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
        $contactFormConstraints = new Assert\Collection(array(
            'firstname' => array(
                new Assert\NotBlank(array('message' => 'contact_form.firstname.blank')),
                new Assert\Regex(array('pattern' => '/[\p{L}][\p{L}\.\- \'\`]+/u', 'match' => true, 'message' => 'contact_form.firstname.invalid_chars')),
                new Assert\Regex(array('pattern' => '/[\d\!\"\£\$\%\^\*\(\)\_\=\+\[\]\{\}\;\:\@\|\,]/', 'match' => false, 'message' => 'contact_form.firstname.invalid_chars')),
                new Assert\Length(array('min' => 2, 'max' => 64, 'minMessage' => 'contact_form.firstname.short', 'maxMessage' => 'contact_form.firstname.long'))
            ),
            'surname' => array(
                new Assert\NotBlank(array('message' => 'contact_form.surname.blank')),
                new Assert\Regex(array('pattern' => '/[\p{L}][\p{L}\.\- \'\`]+/u', 'match' => true, 'message' => 'contact_form.surname.invalid_chars')),
                new Assert\Regex(array('pattern' => '/[\d\!\"\£\$\%\^\*\(\)\_\=\+\[\]\{\}\;\:\@\|\,]/', 'match' => false, 'message' => 'contact_form.surname.invalid_chars')),
                new Assert\Length(array('min' => 2, 'max' => 64, 'minMessage' => 'contact_form.surname.short', 'maxMessage' => 'contact_form.surname.long'))
            ),
            'email' => array(
                new Assert\NotBlank(array('message' => 'contact_form.email.blank')),
                new Assert\Length(array('min' => 2, 'max' => 255, 'minMessage' => 'contact_form.email.short', 'maxMessage' => 'contact_form.email.long')),
                new Assert\Email(array('message' => 'contact_form.email.invalid'))
            ),
            'comment' => array(
                new Assert\NotBlank(array('message' => 'contact_form.comment.blank')),
                new Assert\Length(array('min' => 2, 'max' => 1000, 'minMessage' => 'contact_form.comment.short', 'maxMessage' => 'contact_form.comment.long'))
            ),
            'bottrap' => array(
                new Assert\Blank(array('message' => 'contact_form.bottrap.not_blank')),
                new Assert\Length(array('max' => 1, 'maxMessage' => 'contact_form.bottrap.not_blank'))
            )
        ));

        $resolver->setDefaults(array(
            'intention' => 'contact_form_submit',
            'error_mapping' => array(
            ),
            /*
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // a unique key to help generate the secret token
            'csrf_token_id'   => 'contact_form',
            */
            'constraints' => $contactFormConstraints
        ));
    }

    /**
     * Define the name of the form to call it for rendering
     *
     * @return string
     *
     */
    public function getBlockPrefix() {
        return 'contactform';
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}
