<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Hit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Hit controller.
 *
 */
class HitController extends Controller
{
    /**
     * Lists all hit entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $hits = $em->getRepository('AppBundle:Hit')->findBy(array(), array('visitTime' => 'DESC'));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $hits, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            20/*limit per page*/
        );

        return $this->render('hit/index.html.twig', array(
            'hits' => $pagination,
        ));
    }

    /**
     * Creates a new hit entity.
     *
     */
    public function newAction(Request $request)
    {
        $hit = new Hit();
        $form = $this->createForm('AppBundle\Form\HitType', $hit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($hit);
            $em->flush($hit);

            return $this->redirectToRoute('hit_show', array('id' => $hit->getId()));
        }

        return $this->render('hit/new.html.twig', array(
            'hit' => $hit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a hit entity.
     *
     */
    public function showAction(Hit $hit)
    {
        $deleteForm = $this->createDeleteForm($hit);

        return $this->render('hit/show.html.twig', array(
            'hit' => $hit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing hit entity.
     *
     */
    public function editAction(Request $request, Hit $hit)
    {
        $deleteForm = $this->createDeleteForm($hit);
        $editForm = $this->createForm('AppBundle\Form\HitType', $hit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hit_edit', array('id' => $hit->getId()));
        }

        return $this->render('hit/edit.html.twig', array(
            'hit' => $hit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a hit entity.
     *
     */
    public function deleteAction(Request $request, Hit $hit)
    {
        $form = $this->createDeleteForm($hit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($hit);
            $em->flush($hit);
        }

        return $this->redirectToRoute('hit_index');
    }

    /**
     * Creates a form to delete a hit entity.
     *
     * @param Hit $hit The hit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Hit $hit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hit_delete', array('id' => $hit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
