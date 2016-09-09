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

use Symfony\Component\Form\Extension\Core\Type\TextType;

class FilterUsersFormType extends AbstractType {

    private $class;

    /**
     * Construct form for FilterUsersFormType
     *
     * @param string $class The UserFilters class name
     */
    public function __construct($class) {
        $this->class = $class;
    }

    // Creating the filters form and the fields
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('username', TextType::class, array(
                'label' => 'form.search.username',
                'translation_domain' => 'SonataUserBundle',
                "attr" => [
                    'placeholder' => "username",
                    'minlength' => 2,
                    'maxlength' => 20
                ],
                'required' => true
            ))
        ;
    }

    /**
     * Configure Options for FilterUsersFormType
     * with error mapping for non field errors
     *
     * @param OptionsResolver $resolver
     *
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'filter_users_form'
        ));
    }

    /**
     * Define the name of the form to call it for rendering
     *
     * @return string
     *
     */
    public function getBlockPrefix() {
        return 'sonata_user_filter_users';
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}
