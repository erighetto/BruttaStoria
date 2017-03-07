<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Definition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Definition controller.
 *
 */
class DefinitionController extends Controller
{
    /**
     * Lists all definition entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $definitions = $em->getRepository('AppBundle:Definition')->findBy(array(), array('created' => 'DESC'));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $definitions, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
        );

        return $this->render('definition/index.html.twig', array(
            'definitions' => $pagination,
        ));
    }


    /**
     * Finds and displays a definition entity.
     *
     */
    public function showAction(Definition $definition)
    {
        $deleteForm = $this->createDeleteForm($definition);

        return $this->render('definition/show.html.twig', array(
            'definition' => $definition,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing definition entity.
     *
     */
    public function editAction(Request $request, Definition $definition)
    {
        $deleteForm = $this->createDeleteForm($definition);
        $editForm = $this->createForm('AppBundle\Form\DefinitionType', $definition);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $purifier = $this->get('app.htmlsanitization');
            $definition->setBody($purifier->purify($definition->getBody()));
            $definition->setExtraInfo($purifier->purify($definition->getExtraInfo()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('definition_edit', array('id' => $definition->getId()));
        }

        return $this->render('definition/edit.html.twig', array(
            'definition' => $definition,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a definition entity.
     *
     */
    public function deleteAction(Request $request, Definition $definition)
    {
        $form = $this->createDeleteForm($definition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($definition);
            $em->flush($definition);
        }

        return $this->redirectToRoute('definition_index');
    }

    /**
     * Creates a form to delete a definition entity.
     *
     * @param Definition $definition The definition entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Definition $definition)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('definition_delete', array('id' => $definition->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
