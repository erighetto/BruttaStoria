<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Node;
use AppBundle\Entity\Definition;
use AppBundle\Entity\User;

/**
 * Class BackendController
 * @package AppBundle\Controller
 */
class BackendController extends Controller
{
    /**
     * @var
     */
    protected $current_user;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $current_user = $container->get('security.token_storage')->getToken()->getUser();
        $this->current_user = $current_user;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function new_nodeAction(Request $request)
    {
        $node = new Node();
        $form = $this->createForm('AppBundle\Form\NodeType', $node, ['role' => $this->current_user->getRoles()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $check = $em->getRepository('AppBundle:Node')
                ->findOneByTitle(['title' => $node->getTitle()]);

            if ($check) throw new \Exception('La parola esiste già');

            $slug = $this->get('app.slugmanager')->generate($node->getTitle());
            $node->setSlug($slug);

            $em->persist($node);
            $em->flush();

            return $this->redirectToRoute('backend_new_definition', array('node_id' => $node->getId()));
        }
        return $this->render('backend/new.node.html.twig', array(
            'node' => $node,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new_definitionAction(Request $request)
    {
        $definition = new Definition();
        $definition->setNodeId($request->get('node_id'));
        $definition->setUserId($this->current_user->getId());
        $form = $this->createForm('AppBundle\Form\DefinitionType', $definition, ['role' => $this->current_user->getRoles()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $purifier = $this->get('app.htmlsanitization');
            $definition->setBody($purifier->purify($definition->getBody()));
            $definition->setExtraInfo($purifier->purify($definition->getExtraInfo()));
            $em->persist($definition);
            $em->flush();

            return $this->redirectToRoute('page_thankyou');
        }

        return $this->render('backend/new.definition.html.twig', array(
            'definition' => $definition,
            'form' => $form->createView(),
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profile_viewAction()
    {
        return $this->render('backend/profile.view.html.twig', array(
            'user' => $this->current_user,
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function profile_editAction(Request $request)
    {
        $user = $this->current_user;
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profile_resetAction()
    {
        $user = $this->current_user;
        return $this->render('backend/profile.reset.html.twig', array(// ...
        ));
    }

}
