<?php

/*
 * ContentBlock Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\ContentBlockBundle\Admin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManager;

class ContentGlobalBlockType extends AbstractType {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options) {
        $formBuilder
                ->add('contentblock', 'entity', array(
                    'auto_initialize' => false,
                    'class' => 'BardisCMS\ContentBlockBundle\Entity\ContentBlock',
                    'query_builder' => $this->getGlobalBlocksQueryBuilder(),
                    'choice_label' => 'title',
                    'expanded' => false,
                    'multiple' => false,
                    'attr' => array(
                        'class' => 'autoCompleteItems autoCompleteGlobalContentBlocks',
                        'data-sonata-select2' => 'false'
                    ),
                    'label' => 'Select Content Block',
                    'required' => true
                ))
        ;
    }

    // Function to retrieve the content blocks that are globally available
    public function getGlobalBlocksQueryBuilder() {

        // Initalize the query builder variables
        $qb = $this->entityManager->createQueryBuilder();
        $availability = 'global';
        $excludedContentType = 'globalblock';

        // The query to get all global content blocks
        $qb->select('DISTINCT b')
                ->from('ContentBlockBundle:ContentBlock', 'b')
                ->where(
                        $qb->expr()->eq('b.availability', ':availability'),
                        $qb->expr()->neq('b.contentType', ':excludedContentType')
                )
                ->orderBy('b.title', 'ASC')
                ->setParameter('availability', $availability)
                ->setParameter('excludedContentType', $excludedContentType)
        ;

        return $qb;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $optionsNormalizer = function (Options $options, $value) {
            $value = 'BardisCMS\ContentBlockBundle\Entity\ContentGlobalBlock';

            return $value;
        };

        $resolver->setNormalizer('data_class', $optionsNormalizer);
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    // Define the name of the form to call it for rendering
    public function getBlockPrefix() {
        return 'contentglobalblock';
    }
}
