<?php

namespace AppBundle\EventListener;

use CoopTilleuls\ForgotPasswordBundle\Event\ForgotPasswordEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

/**
 * Class ForgotPasswordEventListener
 * @package AppBundle\EventListener
 */
class ForgotPasswordEventListener
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @param Router $router
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer $mailer
     */
    public function __construct(Router $router, \Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->router = $router;
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * @param ForgotPasswordEvent $event
     */
    public function onCreateToken(ForgotPasswordEvent $event)
    {
        $passwordToken = $event->getPasswordToken();
        $user = $passwordToken->getUser();

        $swiftMessage = new \Swift_Message(
            'Reset of your password',
            $this->twig->render(
                'mail/reset.password.html.twig',
                [
                    'reset_password_url' => $this->router->generate(
                        'coop_tilleuls_forgot_password.get_token',
                        ['tokenValue' => $passwordToken->getToken()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ]
            )
        );

        $swiftMessage->setFrom('no-reply@bruttastoria.it');
        $swiftMessage->setTo($user->getEmail());
        $swiftMessage->setContentType('text/html');
        if (0 === $this->mailer->send($swiftMessage)) {
            throw new \RuntimeException('Unable to send email');
        }
    }
}