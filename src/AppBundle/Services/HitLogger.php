<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Hit;


/**
 * Class HitLogger
 * @package AppBundle\Services
 */
class HitLogger
{
    /**
     * HitLogger constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $nodeId
     */
    public function writeRecord($nodeId) {

        if( !isset( $HTTP_REFERER ) )
        {	// If this magic variable is not already set:
            if( isset($_SERVER['HTTP_REFERER']) )
            {	// This would be the best way to get the referrer,
                // unfortunatly, it's not always avilable!! :[
                // If someone has a clue about this, I'd like to hear about it ;)
                $HTTP_REFERER = $_SERVER['HTTP_REFERER'];
            }
            else
            {	// Fallback method (not thread safe :[[ )
                $HTTP_REFERER = getenv('HTTP_REFERER');
            }
        }

        $ref = $HTTP_REFERER;
        $RemoteAddr = $_SERVER['REMOTE_ADDR'];
        $UserAgent = $_SERVER['HTTP_USER_AGENT'];

        if ($UserAgent != strip_tags($UserAgent))
        { //then they have tried something funny,
            //putting HTML or PHP into the HTTP_REFERER
            $UserAgent = '';
        }

        $date = new \DateTime();

        $entity = New Hit();
        $entity->setHitRemoteAddr($RemoteAddr);
        $entity->setHitUserAgent($UserAgent);
        $entity->setNodeId($nodeId);
        $entity->setReferingUrl($ref);
        $entity->setVisitTime($date);

        $this->em->persist($entity);
        $this->em->flush();
        $this->em->clear();
    }
}