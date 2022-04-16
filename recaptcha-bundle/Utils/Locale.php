<?php

namespace Anysrv\RecaptchaBundle\Utils;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Locale
 * @package Anysrv\RecaptchaBundle\Utils
 */
class Locale
{
    /**
     * @var string
     */
    private $overwriteLocale;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Locale constructor
     *
     * @param string $overwriteLocale
     * @param RequestStack $requestStack
     */
    public function __construct($overwriteLocale, RequestStack $requestStack)
    {
        $this->overwriteLocale = $overwriteLocale;
        $this->requestStack = $requestStack;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function resolve()
    {
        return $this->overwriteLocale !== null
            ? $this->overwriteLocale
            : $this->requestStack->getCurrentRequest()->getLocale();
    }
}