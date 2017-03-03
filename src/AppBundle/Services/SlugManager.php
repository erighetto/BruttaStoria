<?php


namespace AppBundle\Services;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Node;


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

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

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
     * @return string
     */
    public function generate($string) {
        $manager = New Slugify();
        $slug = $manager->slugify($string);
        if (strpos($slug,self::POST_FIX) === false) {
            $slug = $this->addPostfix($slug);
        }
        $count = $this->em->getRepository('AppBundle:Node')
            ->find(['slug' => $slug])->count();
        return $slug;
    }

}