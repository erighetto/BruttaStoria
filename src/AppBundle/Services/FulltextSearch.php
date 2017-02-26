<?php

namespace AppBundle\Services;

use Symfony\Component\Form\FormFactory;
use AppBundle\Form\FulltextSearchType;
use Symfony\Component\Routing\Router;

/**
 * Class FulltextSearch
 * @package AppBundle\Services
 */
class FulltextSearch
{

    /**
     * @var FormFactory
     */
    private $formFactory;
    /**
     * @var Router
     */
    private $router;

    /**
     * FulltextSearch constructor.
     * @param Router $router
     * @param FormFactory $formFactory
     */
    public function __construct(Router $router, FormFactory $formFactory)
    {
        $this->router = $router;
        $this->formFactory = $formFactory;
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    public function getSearchForm()
    {
        $form = $this->formFactory->createBuilder(FulltextSearchType::class)
            ->setAction($this->router->generate('search_node'))
            ->getForm();

        return $form->createView();
    }

}