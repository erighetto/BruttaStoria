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
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function reset_passwordAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('email', TextType::class, ['label' => 'Email'])
            ->add('reset', SubmitType::class, ['label' => 'Reset Password', 'attr' => ['class' => 'btn btn-warning']])
            ->getForm();
        $form->handleRequest($request);

        $response = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $body = $this->json(['email' => $data['email']]);
            $client = new Client(['proxy' => 'tcp://' . $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['SERVER_PORT']]);
            $url = $this->generateUrl('coop_tilleuls_forgot_password.reset', array(), UrlGeneratorInterface::ABSOLUTE_URL);
            $request = new GuzzleRequest('POST', $url, [], $body->getContent());

            try {
                $response = $client->send($request, ['timeout' => 20]);
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    echo Psr7\str($e->getResponse());
                }
            }
        }

        if ($response) {
            $message = json_decode($response->getBody(), true);
            dump($message);
            die;
        }

        return $this->render('security/reset.password.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}