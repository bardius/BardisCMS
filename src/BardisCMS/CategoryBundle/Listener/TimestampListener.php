<?php

/*
 * Category Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\CategoryBundle\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;

class TimestampListener {

    public function onFlush(OnFlushEventArgs $args) {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $entities = array_merge(
                $uow->getScheduledEntityInsertions(), $uow->getScheduledEntityUpdates()
        );

        foreach ($entities as $entity) {
            if (!(get_class($entity) == 'BardisCMS\CategoryBundle\Entity\Category')) {
                continue;
            }

            $taggedEntities = array(
                array('taggedPage' => $entity->getPages(), 'classMetadata' => 'BardisCMS\PageBundle\Entity\Page'),
                array('taggedPage' => $entity->getBlogs(), 'classMetadata' => 'BardisCMS\BlogBundle\Entity\Blog')
            );


            foreach ($taggedEntities as $taggedEntity) {

                foreach ($taggedEntity['taggedPage'] as $taggedPage) {

                    $taggedPage->setDateLastModified($entity->getDateLastModified());

                    $em->persist($taggedPage);
                    $md = $em->getClassMetadata($taggedEntity['classMetadata']);
                    $uow->recomputeSingleEntityChangeSet($md, $taggedPage);
                }
            }
        }
    }

}
