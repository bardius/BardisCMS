<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenericDetailsFormType extends AbstractType
{
    private $class;
    private $container;

    /**
     * Construct form for GenericDetailsFormType.
     *
     * @param string    $class     The User class name
     * @param Container $container
     */
    public function __construct($class, Container $container)
    {
        $this->class = $class;
        $this->container = $container;
    }

    /**
     * Build form for GenericDetailsFormType.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Set up variable values
        $now = new \DateTime();
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Load values from persisted User data
        $defaults = array(
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'title' => $user->getTitle(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'gender' => $user->getGender(),
            'dateOfBirth' => $user->getDateOfBirth(),
        );

        // Adding custom extra user fields for Generic Details Form
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'form.email',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['email'],
                'attr' => array(
                    'placeholder' => 'user@domain.com',
                    'minlength' => 2,
                    'maxlength' => 255,
                ),
                'required' => true,
            ))
            ->add('username', TextType::class, array(
                'label' => 'form.username',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['username'],
                'attr' => array(
                    'placeholder' => 'username',
                    'minlength' => 6,
                    'maxlength' => 20,
                ),
                'required' => true,
                'read_only' => true,
            ))
            /*
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'SonataUserBundle',
                    'error_bubbling' => false,
                ),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch'
            ))
            */
            ->add('title', ChoiceType::class, array(
                'choices' => array(
                    User::TITLE_MR => 'mr',
                    User::TITLE_MS => 'ms',
                    User::TITLE_MRS => 'mrs',
                    User::TITLE_MISS => 'miss',
                    User::TITLE_DR => 'dr',
                    User::TITLE_PROF => 'prof',
                ),
                'label' => 'form.title',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['title'],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'form.firstname',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['firstname'],
                'attr' => array(
                    'placeholder' => 'Name',
                    'minlength' => 2,
                    'maxlength' => 50,
                ),
                'required' => false,
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'form.lastname',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['lastname'],
                'attr' => array(
                    'placeholder' => 'Surname',
                    'minlength' => 2,
                    'maxlength' => 50,
                ),
                'required' => false,
            ))
            ->add('gender', ChoiceType::class, array(
                'choices' => array(
                    User::GENDER_UNKNOWN => 'gender_unknown',
                    User::GENDER_FEMALE => 'gender_female',
                    User::GENDER_MALE => 'gender_male',
                ),
                'label' => 'form.gender',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['gender'],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ))
            ->add('dateOfBirth', DateType::class, array(
                'format' => 'dd-MM-yyyy',
                'widget' => 'single_text',
                'label' => 'form.dateOfBirth',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['dateOfBirth'],
                'placeholder' => 'dd-mm-yyyy',
                'html5' => true,
                'error_bubbling' => false,
                'attr' => array(
                    'class' => 'datepickerField',
                    'data-date-language' => $this->container->get('translator')->getLocale(),
                    'data-picker-position' => 'bottom-right',
                    'data-date-format' => 'dd-mm-yyyy',
                    'placeholder' => 'dd-mm-yyyy',
                    'minlength' => 10,
                    'maxlength' => 10,
                ),
                'invalid_message' => 'dateOfBirth.isNotDate',
                'required' => false,
            ))
        ;
    }

    /**
     * Configure Options for GenericDetailsFormType
     * with error mapping for non field errors.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'profile_generic_details_edit',
            'error_mapping' => array(
                'validDateOfBirth' => 'dateOfBirth',
                'safePassword' => 'plainPassword',
            ),
        ));
    }

    /**
     * Define the name of the form to call it for rendering.
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'sonata_user_generic_details';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}
