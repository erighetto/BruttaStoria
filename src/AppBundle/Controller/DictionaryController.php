<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

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
            ->setParameter('regexp', '^[A-Za-z]')
            ->andWhere('a.status = 1')
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

    public function single_nodeAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $node = $em->getRepository('AppBundle:Node')
            ->findOneBySlug($slug);

        if (!$node) {
            throw $this->createNotFoundException(
                'No word found'
            );
        } else {
            $id = $node->getId();
            $definitions = $em->getRepository('AppBundle:Definition')->relatedDefinitions($id);
            $related = $em->getRepository('AppBundle:Node')->relatedNode($id);
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
