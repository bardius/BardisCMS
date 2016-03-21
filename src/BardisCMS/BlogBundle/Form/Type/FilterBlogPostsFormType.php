<?php

/*
 * Blog Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;

class FilterBlogPostsFormType extends AbstractType {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

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
                'query_builder' => $this->getFilters('Homepage'),
                'choice_label' => 'title',
                'expanded' => true,
                'multiple' => true,
                'label' => 'Categories',
                'required' => false,
            )
        );
    }

    public function getFilters($title) {

        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('DISTINCT c')
            ->from('CategoryBundle:Category', 'c')
            ->where($qb->expr()->andX(
                $qb->expr()->neq('c.title', ':title')
            ))
            ->orderBy('c.title', 'DESC')
            ->setParameter('title', $title)
        ;

        return $qb;
    }

    /**
     * Define the name of the form to call it for rendering
     *
     * @return string
     *
     */
    public function getBlockPrefix() {
        return 'filterblogpostsform';
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}
