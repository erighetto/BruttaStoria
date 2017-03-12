<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query;


/**
 * Class DefinitionRepository
 * @package AppBundle\Repository
 */
class DefinitionRepository extends EntityRepository
{

    /**
     * @param $node_id
     * @return array
     */
    function findRelatedDefinitions($node_id)
    {

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select(array('d', 'u'))
            ->from('AppBundle:Definition', 'd')
            ->innerJoin('AppBundle:User', 'u', Join::WITH, 'd.userId = u.id')
            ->where('d.status = 1')
            ->andWhere('d.nodeId = :node_id')
            ->orderBy('d.poll', 'DESC')
            ->setParameter('node_id', $node_id);

        $query = $qb->getQuery();
        $results = $query->getResult(Query::HYDRATE_SCALAR);

        return $results;
    }

    /**
     * @return Query
     */
    function findAllforBackend()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('d','n')
            ->from('AppBundle:Definition', 'd')
            ->innerJoin('AppBundle:Node', 'n', Join::WITH, 'd.nodeId = n.id')
            ->orderBy('d.created', 'DESC');
        $query = $qb->getQuery();
        $query->setHydrationMode(Query::HYDRATE_SCALAR);

        return $query;
    }

}