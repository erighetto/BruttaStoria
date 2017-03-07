<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{

    public function manifestoAction()
    {
        return $this->render('page/manifesto.html.twig', array(
            // ...
        ));
    }

    public function privacy_policyAction()
    {
        return $this->render('page/privacy_policy.html.twig', array(
            // ...
        ));
    }

    public function thankyouAction()
    {
        return $this->render('page/privacy_policy.html.twig', array(
            // ...
        ));
    }

}
