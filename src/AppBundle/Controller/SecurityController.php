<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class SecurityController
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
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
        return $this->render('security/new.user.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reset_passwordAction(Request $request)
    {

        $form = $this->createFormBuilder()
            ->add('email', TextType::class, array('label' => 'Email'))
            ->add('reset', SubmitType::class, array('label' => 'Reset Password', 'attr' => array('class' => 'btn btn-warning')))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $body = $this->json(array('email' => 'redazione.bruttastoria@gmail.com'));
            $client = new Client();
            $url = $this->generateUrl('coop_tilleuls_forgot_password.reset',array(),UrlGeneratorInterface::ABSOLUTE_URL);
            $request = new GuzzleRequest('POST', $url, array(), $body);
            $response = $client->send($request, ['timeout' => 20]);
            dump($response);die;
        }

        return $this->render('security/reset.password.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}