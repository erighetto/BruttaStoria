<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sticked = $em->getRepository('AppBundle:Node')->topTenSticked();
        $promoted = $em->getRepository('AppBundle:Node')->topTenSticked();

        return $this->render('default/index.html.twig', [
            'sticked_nodes'=> $sticked,
            'promoted_nodes'=> $promoted,
        ]);
    }
}
