<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        /*// create example form
        $exampleForm = $this->createForm(ExampleType::class, new Example());

        // initially, the message shown to the visitor is empty
        $message = '';

        $exampleForm ->handleRequest($request);
        if ($exampleForm->isValid()) {
            // Captcha validation passed
            $message = 'CAPTCHA validation passed, human visitor confirmed!';
        }*/

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

}