<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Node;
use AppBundle\Entity\Definition;
use AppBundle\Entity\User;

class BackendController extends Controller
{
    public function new_userAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('backend_profile_view');
        }
        return $this->render('backend/new.user.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    public function new_nodeAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $node = new Node();
        $form = $this->createForm('AppBundle\Form\NodeType', $node, ['role' => $user->getRoles()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $check = $em->getRepository('AppBundle:Node')
                ->findOneByTitle(['title' => $node->getTitle()]);

            if ($check) throw new \Exception('La parola esiste già');

            $slug = $this->get('app.slugmanager')->generate($node->getTitle());
            $node->setSlug($slug);
            $node->setCreated(New \DateTime());
            $node->setUpdated(New \DateTime());

            $em->persist($node);
            $em->flush();

            return $this->redirectToRoute('backend_new_definition', array('node_id' => $node->getId()));
        }
        return $this->render('backend/new.node.html.twig', array(
            'node' => $node,
            'form' => $form->createView(),
        ));
    }

    public function new_definitionAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $definition = new Definition();
        $form = $this->createForm('AppBundle\Form\DefinitionType', $definition, ['role' => $user->getRoles()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($definition);
            $em->flush();

            return $this->redirectToRoute('page_thankyou');
        }

        return $this->render('backend/new.definition.html.twig', array(
            'definition' => $definition,
            'form' => $form->createView(),
        ));
    }

    public function profile_viewAction() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('backend/profile.view.html.twig', array(
            'user' => $user,
        ));
    }

    public function profile_editAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $editForm = $this->createForm('AppBundle\Form\UserType', $user, ['role' => $user->getRoles()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_profile_view');
        }
        return $this->render('backend/profile.edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
        ));
    }

    public function profile_resetAction() {
        return $this->render('backend/profile.reset.html.twig', array(
            // ...
        ));
    }

}
