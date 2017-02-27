<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * Home Page
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sticked = $em->getRepository('AppBundle:Node')->topTenSticked();
        $promoted = $em->getRepository('AppBundle:Node')->topTenPromoted();

        return $this->render('default/index.html.twig', [
            'sticked_nodes'=> $sticked,
            'promoted_nodes'=> $promoted,
        ]);
    }
}
