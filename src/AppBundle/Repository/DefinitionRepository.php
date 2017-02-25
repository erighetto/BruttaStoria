<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25/02/17
 * Time: 10:18
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class DefinitionRepository extends EntityRepository
{

    function relatedDefinitions($node_id) {
        $em =  $this->getEntityManager();
        $em->createQuery('SELECT d, u
		FROM AppBundle:Definition d
		JOIN AppBundle:User u ON d.userId = u.id
		WHERE (d.status = 1) AND (d.nodeId = :node_id)
		ORDER BY d.poll DESC')
            ->setParameter('node_id', $node_id);

        return $em->getResult();
    }

}