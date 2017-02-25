<?php


namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class NodeRepository
 * @package AppBundle\Repository
 */
class NodeRepository extends EntityRepository
{

    function topTenSticked() {

        $em =  $this->getEntityManager();
        $em->createQuery('SELECT n.title, n.slug, d.body
		FROM AppBundle:Node n
		JOIN AppBundle:Definition d ON (d.nodeId = n.id AND d.status = 1)
		WHERE (n.status = 1) AND (n.sticky = :sticky)
		ORDER BY n.updated DESC')
            ->setParameter('sticky', 1)
            ->setFirstResult( 0 )
            ->setMaxResults( 10 );

        return $em->getResult();

    }

    function topTenPromoted() {
        $em =  $this->getEntityManager();
        $em->createQuery('SELECT n.title, n.slug, d.body
		FROM AppBundle:Node n
		JOIN AppBundle:Definition d ON (d.nodeId = n.id AND d.status = 1)
		WHERE (n.status = 1) AND (n.promoted = :promoted)
		ORDER BY n.updated DESC')
            ->setParameter('promoted', 1)
            ->setFirstResult( 0 )
            ->setMaxResults( 10 );

        return $em->getResult();
    }
}