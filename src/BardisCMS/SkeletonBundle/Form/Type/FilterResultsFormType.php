<?php

/*
 * Skeleton Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\SkeletonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class FilterResultsFormType extends AbstractType {

    // Creating the filters form and the fields
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('tags', 'entity', array(
                'class' => 'BardisCMS\TagBundle\Entity\Tag',
                'choice_label' => 'title',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Tags',
                'required' => false,
            )
        );

        $builder->add('categories', 'entity', array(
                'class' => 'BardisCMS\CategoryBundle\Entity\Category',
                'choice_label' => 'title',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Categories',
                'required' => false,
            )
        );
    }

    /**
     * Define the name of the form to call it for rendering
     *
     * @return string
     *
     */
    public function getBlockPrefix() {
        return 'filterresultsform';
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}
