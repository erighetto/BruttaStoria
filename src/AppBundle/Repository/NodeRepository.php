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
    function topTenSticked()
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select(array('DISTINCT n.id', 'n.title', 'n.slug', 'd.body'))
            ->from('AppBundle:Node', 'n')
            ->innerJoin('AppBundle:Definition', 'd', Join::WITH, 'd.nodeId = n.id AND d.status = 1')
            ->where('n.status = 1')
            ->andWhere('n.sticky = :sticky')
            ->setParameter('sticky', 1)
            ->setFirstResult(0)
            ->setMaxResults(10);
        $query = $qb->getQuery();
        $results = $query->getResult();

        return $results;

    }

    /**
     * @return array
     */
    function topTenPromoted()
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select(array('DISTINCT(n.id)', 'n.title', 'n.slug', 'd.body', 'n.updated'))
            ->from('AppBundle:Node', 'n')
            ->innerJoin('AppBundle:Definition', 'd', Join::WITH, 'd.nodeId = n.id AND d.status = 1')
            ->where('n.status = 1')
            ->andWhere('n.promote = :promoted')
            ->orderBy('n.updated', 'DESC')
            ->setParameter('promoted', 1)
            ->setFirstResult(0)
            ->setMaxResults(10);
        $query = $qb->getQuery();
        $results = $query->getResult();

        return $results;
    }

    /**
     * @return array
     */
    function relatedNode($nodeId)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select(array('n', 'r'))
            ->from('AppBundle:Node', 'n')
            ->innerJoin('AppBundle:Relation', 'r', Join::WITH, 'r.relatedNodeId = n.id')
            ->where('n.status = 1')
            ->andWhere('r.nodeId = :node_id')
            ->orderBy('n.title', 'ASC')
            ->setParameter('node_id', $nodeId);
        $query = $qb->getQuery();
        $results = $query->getResult(Query::HYDRATE_SCALAR);

        return $results;
    }
}