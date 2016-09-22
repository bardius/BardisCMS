<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\BlogBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BlogAdminController extends Controller
{
    public function duplicateAction($id = null)
    {
        // the key used to lookup the template
        $templateKey = 'edit';

        $id = $this->get('request')->get($this->admin->getIdParameter());

        $clonedObject = $this->admin->getObject($id);
        $clonedObject->setTitle($clonedObject->getTitle().' Clone');
        $clonedObject->setAlias($clonedObject->getAlias().'-clone');
        $date = new \DateTime();
        $clonedObject->setDate($date);

        $object = $this->admin->getNewInstance();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the Blog Page with id : %s', $id));
        }

        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        $form = $this->admin->getForm();
        $form->setData($clonedObject);

        foreach ($form->getData()->getMaincontentblocks() as $maincontentblock) {
            unset($maincontentblock);
        }

        foreach ($form->getData()->getBannercontentblocks() as $bannercontentblock) {
            unset($bannercontentblock);
        }

        foreach ($form->getData()->getModalcontentblocks() as $modalcontentblock) {
            unset($modalcontentblock);
        }

        if ($this->get('request')->getMethod() === 'POST') {
            $form->handleRequest($this->get('request'));

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $this->admin->create($object);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                                'result' => 'ok',
                                'objectId' => $this->admin->getNormalizedIdentifier($object),
                    ));
                }

                $this->get('session')->setFlash('sonata_flash_success', 'flash_create_success');
                // redirect to edit mode
                return $this->redirectTo($object);
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                $this->get('session')->setFlash('sonata_flash_error', 'flash_create_error');
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
                    'action' => 'create',
                    'form' => $view,
                    'object' => $object,
        ));
    }
}
