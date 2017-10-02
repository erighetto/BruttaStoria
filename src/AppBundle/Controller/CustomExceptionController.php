<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class CustomExceptionController
 * @package AppBundle\Controller
 */
class CustomExceptionController extends ExceptionController
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * CustomExceptionController constructor.
     * @param Environment $twig
     * @param $debug
     * @param EntityManager $entityManager
     */
    public function __construct(Environment $twig, $debug, EntityManager $entityManager)
    {
        parent::__construct($twig, $debug);
        $this->entityManager = $entityManager;
    }


    /**
     * @inheritDoc
     */
    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null)
    {

        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
        $showException = $request->attributes->get('showException', $this->debug); // As opposed to an additional parameter, this maintains BC

        $code = $exception->getStatusCode();

        if ($code == '404') {
            $pathInfo = $request->getPathInfo();
            $sentence = trim(str_replace(['/dictionary/', '.html', '.xml', '-', '_', '%'], ' ', $pathInfo));
            $sentence = preg_replace("/[^a-zA-Z 0-9]+/", "", $sentence);
            $words = explode(' ', $sentence);
            $repo = $this->entityManager->getRepository('AppBundle:Node');
            $query = $repo->createQueryBuilder('a')
                ->where('a.title LIKE :title')
                ->setParameter('title', '%'.$words[0].'%')
                ->getQuery();

            $nodes = $query->getResult();
        } else {
            $nodes = false;
        }

        if ($nodes) {
            $this->twig->enableAutoReload();
            $list = $this->twig->render(
                'exception/suggestions.html.twig',
                array(
                    'nodes' => $nodes,
                )
            );
            $currentContent = $currentContent . $list;
        }


        return new Response($this->twig->render(
            (string) $this->findTemplate($request, $request->getRequestFormat(), $code, $showException),
            array(
                'status_code' => $code,
                'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                'exception' => $exception,
                'logger' => $logger,
                'currentContent' => $currentContent,
            )
        ));
    }

}
