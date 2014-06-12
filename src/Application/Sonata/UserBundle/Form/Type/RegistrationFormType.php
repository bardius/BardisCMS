<?php
/*
 * User Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;


class RegistrationFormType extends BaseType
{
    private $campaignData;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		/*
        $this->campaignData     = null;
        $this->campaignData     = explode('/', $_SERVER['REQUEST_URI']);
        $this->campaignData     = end($this->campaignData);
		*/
        $this->campaignData		= 'registration';
			
        parent::buildForm($builder, $options);

        // add your custom fields
        $builder
            ->add('firstname', 'text', array('label' => 'First Name*', 'required' => true))
            ->add('lastname', 'text', array('label' => 'Surname*', 'required' => true))
            ->add('email', 'email', array('label' => 'Email*'))
            ->add('sex', 'choice', array('choices' => array('female' => 'Female', 'male' => 'Male'), 'label' => 'Gender', 'required' => false, 'expanded'  => true, 'multiple'  => false))
            ->add('bakeFrequency', 'choice', array('choices' => array( 'week' => 'Every week', 'month' => 'Once a month', 'year' => 'Once a year'), 'label' => 'How often do you bake?', 'required' => false))
            ->add('bakeChoises', 'choice', array('choices' => array('white_caster' => 'White Caster', 'white_grandulated' => 'White Grandulated', 'icing_sugar' => 'Icing Sugar', 'demerara' => 'Demerara', 'light_soft_brown' => 'Light Soft Brown', 'dark_soft_brown' => 'Dark Soft Brown', 'golden_granulated' => 'Golden Granulated', 'golden_caster' => 'Golden Caster', 'muscovado' => 'Muscovado'), 'label' => 'Which of these sugars do you buy regularly?', 'required' => false, 'expanded'  => true, 'multiple'  => true))
            ->add('children', 'choice', array('choices' => array('yes' => 'Yes', 'no' => 'No'), 'label' => 'Do you have children?', 'required' => false, 'expanded'  => true, 'multiple'  => false))
            ->add('age', 'choice', array('choices' => array('Under_18' => 'Under 18', '18-24' => '18-24', '25-34' => '25-34', '35-44' => '35-44', '45-54' => '45-54', '55-64' => '55-64', '65+' => '65+'), 'label' => 'Age', 'required' => false, 'expanded'  => false, 'multiple'  => false))
            ->add('campaign', 'text', array('label' => 'Campaign Name', 'data' => $this->campaignData, 'required' => false))
            ->remove('username')
            ->remove('plainPassword')
        ;
    }

    public function getName()
    {
        return 'registration';
    }
}