<?php


namespace AppBundle\Services;
use Cocur\Slugify\Slugify;

/**
 * Class SlugManager
 * @package AppBundle\Services
 */
class SlugManager
{

    /**
     * estensione
     */
    const POST_FIX = '.html';

    /**
     * @param $string
     * @return string
     */
    public function addPostfix($string) {
        $string = $string . self::POST_FIX;
        return $string;
    }

    /**
     * @param $string
     * @return mixed
     */
    private function removePostfix($string) {
        $string = str_replace(self::POST_FIX,'',$string);
        return $string;
    }

    /**
     * @param $string
     * @return string
     */
    public function in($string) {
        $manager = New Slugify();
        $string = $manager->slugify($string);
        if (strpos($string,self::POST_FIX) === false) {
            $string = $this->addPostfix($string);
        }
        return $string;
    }

    /**
     * @param $string
     * @return mixed
     */
    public function out($string) {
        return $this->removePostfix($string);
    }
}