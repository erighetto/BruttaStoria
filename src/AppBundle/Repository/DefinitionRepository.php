<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25/02/17
 * Time: 10:18
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query;


class DefinitionRepository extends EntityRepository
{

    function relatedDefinitions($node_id) {

        $em =  $this->getEntityManager();
        $qb =  $em->createQueryBuilder();
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

}