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

}
