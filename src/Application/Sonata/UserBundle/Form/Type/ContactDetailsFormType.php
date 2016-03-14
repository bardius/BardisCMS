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

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Application\Sonata\UserBundle\Entity\User;

class ContactDetailsFormType extends AbstractType {

	private $class;
	private $container;

    /**
     * Construct form for ContactDetailsFormType
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
     * Build form for ContactDetailsFormType
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
	public function buildForm(FormBuilderInterface $builder, array $options) {
        // Set up variable values
		$user = $this->container->get('security.context')->getToken()->getUser();

        // Load values from persisted User data
		$defaults = array(
			'addressLine1' => $user->getAddressLine1(),
			'addressLine2' => $user->getAddressLine2(),
			'addressLine3' => $user->getAddressLine3(),
			'city' => $user->getCity(),
			'county' => $user->getCounty(),
            'postcode' => $user->getPostcode(),
            'countryCode' => $user->getCountryCode(),
            'phone' => $user->getPhone(),
            'mobile' => $user->getMobile()
		);

        // Adding custom extra user fields for Contact Details Form
		$builder
            ->add('addressLine1', TextType::class, array(
                'label' => 'form.addressLine1',
                'translation_domain' => 'SonataUserBundle',
                'data'=> $defaults['addressLine1'],
                "attr" => [
                    'placeholder' => "Address Line 1",
                    'minlength' => 2,
                    'maxlength' => 100
                ],
                'required' => true
            ))
            ->add('addressLine2', TextType::class, array(
                'label' => 'form.addressLine2',
                'translation_domain' => 'SonataUserBundle',
                'data'=> $defaults['addressLine2'],
                "attr" => [
                    'placeholder' => "Address Line 2",
                    'minlength' => 2,
                    'maxlength' => 100
                ],
                'required' => true
            ))
            ->add('addressLine3', TextType::class, array(
                'label' => 'form.addressLine3',
                'translation_domain' => 'SonataUserBundle',
                'data'=> $defaults['addressLine3'],
                "attr" => [
                    'placeholder' => "Address Line 3",
                    'maxlength' => 100
                ],
                'required' => false
            ))
            ->add('city', TextType::class, array(
                'label' => 'form.city',
                'translation_domain' => 'SonataUserBundle',
                'data'=> $defaults['city'],
                "attr" => [
                    'placeholder' => "City",
                    'minlength' => 2,
                    'maxlength' => 64
                ],
                'required' => true
            ))
            ->add('county', TextType::class, array(
                'label' => 'form.county',
                'translation_domain' => 'SonataUserBundle',
                'data'=> $defaults['county'],
                "attr" => [
                    'placeholder' => "County",
                    'minlength' => 2,
                    'maxlength' => 64
                ],
                'required' => false
            ))
            ->add('postcode', TextType::class, array(
                'label' => 'form.postcode',
                'translation_domain' => 'SonataUserBundle',
                'data'=> $defaults['postcode'],
                "attr" => [
                    'placeholder' => "Postcode",
                    'minlength' => 2,
                    'maxlength' => 14
                ],
                'required' => false
            ))
            ->add('countryCode', CountryType::class, array(
                'preferred_choices' => array(
                    User::COUNTRY_EN
                ),
                'label' => 'form.countryCode',
                'translation_domain' => 'SonataUserBundle',
                'data'=> $defaults['countryCode'],
                'required' => true
            ))
            ->add('phone', TextType::class, array(
                'label' => 'form.phone',
                'translation_domain' => 'SonataUserBundle',
                'data'=> $defaults['phone'],
                "attr" => [
                    'placeholder' => "+xx xxxx xxx xxx",
                    'maxlength' => 18
                ],
                'required' => false
            ))
            ->add('mobile', TextType::class, array(
                'label' => 'form.mobile',
                'translation_domain' => 'SonataUserBundle',
                'data'=> $defaults['mobile'],
                "attr" => [
                    'placeholder' => "+xx xxxx xxx xxx",
                    'maxlength' => 18
                ],
                'required' => false
            ))
        ;
	}

    /**
     * Configure Options for ContactDetailsFormType
     * with error mapping for non field errors
     *
     * @param OptionsResolver $resolver
     *
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'profile_contact_details_edit',
        ));
    }

    /**
     * Define the name of the form to call it for rendering
     *
     * @return string
     *
     */
    public function getBlockPrefix() {
        return 'sonata_user_contact_details';
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }

}
