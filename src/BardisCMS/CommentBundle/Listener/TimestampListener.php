<?php

/*
 * Comment Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\CommentBundle\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;

class TimestampListener
{
    public function onFlush(OnFlushEventArgs $args)
    {
		$em = $args->getEntityManager();
		$uow = $em->getUnitOfWork();

		$entities = array_merge(
			$uow->getScheduledEntityInsertions(),
			$uow->getScheduledEntityUpdates()
		);

		foreach ($entities as $entity) {
			if (!(get_class($entity) == 'BardisCMS\CommentBundle\Entity\Comment')) {
				continue;
			}
			
			$commentedEntities = array(
				array( 'commentedPage' => $entity->getBlogPost(), 'classMetadata' => 'BardisCMS\BlogBundle\Entity\Blog')
			);
			
			
			foreach ($commentedEntities as $commentedEntity) {
				
				$commentedEntity['commentedPage']->setDateLastModified($entity->getDateLastModified());
					
				$em->persist($commentedEntity['commentedPage']);
				$md = $em->getClassMetadata($commentedEntity['classMetadata']);
				$uow->recomputeSingleEntityChangeSet($md, $commentedEntity['commentedPage']);
			}
		}
    }
}