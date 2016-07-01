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
use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResettingFormType extends AbstractType
{
    /**
     * Build form for ResettingFormType
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('new', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'SonataUserBundle',
                    'error_bubbling' => false,
                ),
                'first_options' => array(
                    'label' => 'form.new_password'
                ),
                'second_options' => array(
                    'label' => 'form.new_password_confirmation'
                ),
                'invalid_message' => 'sonata_user.password.mismatch'
            ))
        ;
    }

    /**
     * Configure Options for ResettingFormType
     *
     * @param OptionsResolver $resolver
     *
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FOS\UserBundle\Form\Model\ChangePassword',
            'intention' => 'resetting',
            'error_mapping' => array(
                'safePassword' => 'new',
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
        return 'sonata_user_resetting';
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}
