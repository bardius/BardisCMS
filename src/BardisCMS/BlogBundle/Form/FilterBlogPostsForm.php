<?php

/*
 * Blog Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityManager;

class FilterBlogPostsForm extends AbstractType 
{
    
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

	// Creating the filters form and the fields
	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder->add('tags', 'entity', array(
			'class'		=> 'BardisCMS\TagBundle\Entity\Tag',
			'property'	=> 'title',
			'expanded'	=> true,
			'multiple'	=> true,
			'label'		=> 'Tags',
			'required'	=> false,
			)
		);

		$builder->add('categories', 'entity', array(
			'class'			=> 'BardisCMS\CategoryBundle\Entity\Category',
            'query_builder' => $this->getFilters('Homepage'),
			'property'		=> 'title',
			'expanded'		=> true,
			'multiple'		=> true,
			'label'			=> 'Categories',
			'required'		=> false,
			)
		);
	}
    
    public function getFilters($title)
    {
        
        //$filterList = array();
        $qb         = $this->entityManager->createQueryBuilder();  
        
        $qb->select('DISTINCT c')
            ->from('CategoryBundle:Category', 'c')
            ->where($qb->expr()->andX(
                    $qb->expr()->neq('c.title', ':title')
            ))
            ->orderBy('c.title', 'DESC')
            ->setParameter('title', $title)
        ;
        
        return  $qb;
    }

	public function getName() {
		// Define the name of the form to call it for rendering
		return 'filterblogpostsform';
	}

}
