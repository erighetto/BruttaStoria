<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DictionaryController extends Controller
{
    public function list_nodesAction($letter,$page)
    {
        $em    = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder()
            ->select('a')
            ->from('AppBundle:Node','a')
            ->where('a.title LIKE :title')
            ->andWhere('a.status = 1')
            ->setParameter('title', $letter.'%')
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page, /*page number*/
            80/*limit per page*/
        );
        return $this->render('dictionary/list.nodes.html.twig', array(
            'pagination' => $pagination,
            'letter' => $letter,
        ));
    }

    public function list_bysymbol_nodesAction($page)
    {
        $em    = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder()
            ->select('a')
            ->from('AppBundle:Node','a')
            ->where('REGEXP(a.title, :regexp) = false')
            ->andWhere('a.status = 1')
            ->setParameter('regexp', '^[A-Za-z]')
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page, /*page number*/
            80/*limit per page*/
        );
        return $this->render('dictionary/list.nodes.html.twig', array(
            'pagination' => $pagination,
            'letter' => "Simboli",
        ));
    }

    public function search_nodeAction(Request $request)
    {

        $form = $request->get('appbundle_node');

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder()
            ->select('a')
            ->from('AppBundle:Node','a')
            ->where('a.title LIKE :parola')
            ->andWhere('a.status = 1')
            ->setParameter('parola', '%'.$form['parola'].'%')
            ->getQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            1, /*page number*/
            800 /*limit per page*/
        );
        return $this->render('dictionary/list.nodes.html.twig', array(
            'pagination' => $pagination,
            'letter' => $form['parola'],
        ));
    }

    public function single_nodeAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $node = $em->getRepository('AppBundle:Node')
            ->findOneBySlug($slug);

        if (!$node) {
            $second_chance = $em->getRepository('AppBundle:Node')
                ->findOneBySlug(str_replace('_','-',$slug));
            if ($second_chance) {
                return $this->redirectToRoute('single_node', array('slug' => $second_chance->getSlug()), 301);
            } else throw $this->createNotFoundException(
                'No word found'
            );
        } else {
            $id = $node->getId();
            $definitions = $em->getRepository('AppBundle:Definition')->relatedDefinitions($id);
            $related = $em->getRepository('AppBundle:Node')->relatedNode($id);
            $logger = $this->get('app.hitlogger');
            $logger->writeRecord($id);
        }

        return $this->render('dictionary/single.node.html.twig', array(
            'node' => $node,
            'definitions' => $definitions,
            'related' => $related
        ));

    }

    public function vote_nodeAction($id,$action)
    {
        $em = $this->getDoctrine()->getManager();

        $definition = $em->getRepository('AppBundle:Definition')
            ->find($id);

        $actual = $definition->getPoll();

        if ($action=="up") { $voting =  $actual+1; } elseif ($action=="down") { $voting =  $actual-1; }

        $definition->setPoll($voting);
        $em->flush();


        $cookieGuest = array(
            'name'  => 'bs-votazione',
            'value' => $id,
            'path'  => $this->get('router')->generate('vote_node', array('id' => $id,'action' => $action)),
            'time'  => time() + 3600 * 24 * 7
        );

        $cookie = new Cookie($cookieGuest['name'], $cookieGuest['value'], $cookieGuest['time'], $cookieGuest['path']);

        $response = new Response();
        $response->headers->setCookie($cookie);
        return $response->send();
    }
}
