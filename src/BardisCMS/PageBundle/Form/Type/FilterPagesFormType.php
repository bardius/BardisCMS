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

class FilterPagesFormType extends AbstractType {

    // Creating the filters form and the fields
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('tags', 'entity', array(
            'class' => 'BardisCMS\TagBundle\Entity\Tag',
            'property' => 'title',
            'expanded' => true,
            'multiple' => true,
            'label' => 'Tags',
            'required' => false,
                )
        );

        $builder->add('categories', 'entity', array(
            'class' => 'BardisCMS\CategoryBundle\Entity\Category',
            'property' => 'title',
            'expanded' => true,
            'multiple' => true,
            'label' => 'Categories',
            'required' => false,
                )
        );
    }

    public function getName() {
        // Define the name of the form to call it for rendering
        return 'filterpagesform';
    }
}
