<?php

/*
 * Sonata User Bundle Overrides
 * This file is part of the BardisCMS.
 * Manage the extended Sonata User entity with extra information for the users
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordFormType extends AbstractType {

    private $class;
    private $container;

    /**
     * Construct form for ChangePasswordFormType
     *
     * @param string $class The User class name
     * @param Container $container
     *
     */
    public function __construct($class, Container $container) {
        $this->class = $class;
        $this->container = $container;
    }

    /**
     * Build form for ChangePasswordFormType
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Set up variable constraints on the linked password fields
        $constraint = new UserPassword();

        // Adding user fields for Change Password Form
        $builder
            ->add('current_password', PasswordType::class, array(
                'label' => 'form.current_password',
                'translation_domain' => 'SonataUserBundle',
                'mapped' => false,
                'constraints' => $constraint,
            ))
            ->add('new', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array('translation_domain' => 'SonataUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'sonata_user.password.mismatch'
            ))
        ;
    }

    /**
     * Configure Options for ChangePasswordFormType
     * with error mapping for non field errors
     *
     * @param OptionsResolver $resolver
     *
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'change_password',
            'error_mapping' => array(
                'safePassword' => 'new_password',
            )
        ));
    }

    /**
     * Define the name of the form to call it for rendering
     *
     * @return string
     *
     */
    public function getBlockPrefix() {
        return 'sonata_user_change_password';
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}
