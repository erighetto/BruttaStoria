<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Definition
 *
 * @ORM\Table(name="bs_relations")
 */
class Relation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private  $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nodeId;

    /**
     * @ORM\Column(type="integer")
     */
    private $relatedNodeId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNodeId()
    {
        return $this->nodeId;
    }

    /**
     * @param mixed $nodeId
     */
    public function setNodeId($nodeId)
    {
        $this->nodeId = $nodeId;
    }

    /**
     * @return mixed
     */
    public function getRelatedNodeId()
    {
        return $this->relatedNodeId;
    }

    /**
     * @param mixed $relatedNodeId
     */
    public function setRelatedNodeId($relatedNodeId)
    {
        $this->relatedNodeId = $relatedNodeId;
    }


}