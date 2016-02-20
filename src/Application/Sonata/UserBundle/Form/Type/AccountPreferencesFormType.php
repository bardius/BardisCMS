<?php

/*
 * User Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AccountPreferencesFormType extends AbstractType {

	private $class;
	private $container;

	/**
     * @param string $class The User class name
     * @param Container $container
	 */
	public function __construct($class, $container) {
		$this->class = $class;
		$this->container = $container;
	}

	public function buildForm(FormBuilderInterface $builder, array $options) {

		$user = $this->container->get('security.context')->getToken()->getUser();

		$defaults = array(
            'language' => $user->getLanguage(),
            'currencyCode' => $user->getCurrencyCode(),
			'secretQuestion' => $user->getSecretQuestion(),
			'secretQuestionResponse' => $user->getSecretQuestionResponse()
		);

        // Adding custom extra user fields for Account Preferences (including the Security) Form
		$builder
            ->add('language', LanguageType::class, array('label' => 'Language', 'data' => $defaults['language'],  'required' => true))
			->add('currencyCode', ChoiceType::class, array('choices' => array(
                'GBP' => '£' ,
                'USD' => '$',
                'EUR' => '€'
            ), 'label' => 'Currency', 'data' => $defaults['currencyCode'],  'required' => true, 'expanded' => true, 'multiple' => false))
            ->add('secretQuestion', ChoiceType::class, array('choices' => array(
                'Spouse’s middle name' => 'Spouse’s middle name',
                'Mother’s Maiden Name' => 'Mother’s Maiden Name',
                'My favourite player' => 'My favourite player',
                'My first car' => 'My first car',
                'My first pet’s name' => 'My first pet’s name',
                'My first school' => 'My first school',
            ),'label' => 'Challenge', 'data' => $defaults['secretQuestion'], 'required' => true))
            ->add('secretQuestionResponse', TextType::class, array('label' => 'Response', 'data' => $defaults['secretQuestionResponse'], 'required' => false))

        ;
	}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'profile_account_preferences_edit',
        ));
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    // Define the name of the form to call it for rendering
    public function getBlockPrefix() {
        return 'sonata_user_account_preferences';
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }

}
