<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query;

/**
 * Class NodeRepository
 * @package AppBundle\Repository
 */
class NodeRepository extends EntityRepository
{

    /**
     * @return array
     */
    function topTenSticked() {

        $em =  $this->getEntityManager();
        $qb =  $em->createQueryBuilder();
        $qb->select(array('n', 'd'))
            ->from('AppBundle:Node', 'n')
            ->innerJoin('AppBundle:Definition','d', Join::WITH, 'd.nodeId = n.id AND d.status = 1')
            ->where('n.status = 1')
            ->andWhere('n.sticky = :sticky')
            ->orderBy('n.updated', 'DESC')
            ->setParameter('sticky', 1)
            ->setFirstResult( 0 )
            ->setMaxResults( 10 );
        $query = $qb->getQuery();
        $results = $query->getResult(Query::HYDRATE_SCALAR);

        return $results;

    }

    /**
     * @return array
     */
    function topTenPromoted() {

        $em =  $this->getEntityManager();
        $qb =  $em->createQueryBuilder();
        $qb->select(array('n', 'd'))
            ->from('AppBundle:Node', 'n')
            ->innerJoin('AppBundle:Definition','d', Join::WITH, 'd.nodeId = n.id AND d.status = 1')
            ->where('n.status = 1')
            ->andWhere('n.promote = :promoted')
            ->orderBy('n.updated', 'DESC')
            ->setParameter('promoted', 1)
            ->setFirstResult( 0 )
            ->setMaxResults( 10 );
        $query = $qb->getQuery();
        $results = $query->getResult(Query::HYDRATE_SCALAR);

        return $results;
    }
}