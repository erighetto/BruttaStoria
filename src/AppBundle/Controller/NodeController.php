<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Node;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Node controller.
 *
 */
class NodeController extends Controller
{
    /**
     * Lists all node entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $nodes = $em->getRepository('AppBundle:Node')->findBy(array(), array('created' => 'DESC'));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
          $nodes, /* query NOT result */
          $request->query->getInt('page', 1)/*page number*/,
          20/*limit per page*/
        );

        return $this->render('node/index.html.twig', array(
            'nodes' => $pagination,
        ));
    }

    /**
     * Creates a new node entity.
     *
     */
    public function newAction(Request $request)
    {
        $node = new Node();
        $form = $this->createForm('AppBundle\Form\NodeType', $node);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($node);
            $em->flush($node);

            return $this->redirectToRoute('node_show', array('id' => $node->getId()));
        }

        return $this->render('node/new.html.twig', array(
            'node' => $node,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a node entity.
     *
     */
    public function showAction(Node $node)
    {
        $deleteForm = $this->createDeleteForm($node);

        return $this->render('node/show.html.twig', array(
            'node' => $node,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing node entity.
     *
     */
    public function editAction(Request $request, Node $node)
    {
        $deleteForm = $this->createDeleteForm($node);
        $editForm = $this->createForm('AppBundle\Form\NodeType', $node);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('node_edit', array('id' => $node->getId()));
        }

        return $this->render('node/edit.html.twig', array(
            'node' => $node,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a node entity.
     *
     */
    public function deleteAction(Request $request, Node $node)
    {
        $form = $this->createDeleteForm($node);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($node);
            $em->flush($node);
        }

        return $this->redirectToRoute('node_index');
    }

    /**
     * Creates a form to delete a node entity.
     *
     * @param Node $node The node entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Node $node)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('node_delete', array('id' => $node->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
