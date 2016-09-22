<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\PageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterPagesFormType extends AbstractType
{
    // Creating the filters form and the fields
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
     * Define the name of the form to call it for rendering.
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'filterpagesform';
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
