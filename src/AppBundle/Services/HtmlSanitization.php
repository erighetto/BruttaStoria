<?php

namespace AppBundle\Services;

use HTMLPurifier_Config;
use HTMLPurifier;

/**
 * Class HtmlSanitization
 * @package AppBundle\Services
 */
class HtmlSanitization
{

    /**
     * HtmlSanitization constructor.
     */
    public function __construct() {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('CSS.AllowedProperties', array());
        $config->set('HTML.ForbiddenElements', array('font', 'div', 'span', 'pre'));
        $this->config = $config;
    }

    /**
     * @param $html
     * @return string
     */
    public function purify($html) {
        $purifier = new HTMLPurifier($this->config);
        return $purifier->purify($html);
    }

}