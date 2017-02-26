<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hit
 *
 * @ORM\Table(name="bs_hits")
 * @ORM\Entity
 */
class Hit
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="node_id", type="bigint", nullable=true)
     */
    private $nodeId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visit_time", type="datetime", nullable=false)
     */
    private $visitTime = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="refering_url", type="text", nullable=true)
     */
    private $referingUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="hit_remote_addr", type="string", length=15, nullable=true)
     */
    private $hitRemoteAddr;

    /**
     * @var string
     *
     * @ORM\Column(name="hit_user_agent", type="text", nullable=true)
     */
    private $hitUserAgent;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nodeId
     *
     * @param integer $nodeId
     *
     * @return Hit
     */
    public function setNodeId($nodeId)
    {
        $this->nodeId = $nodeId;

        return $this;
    }

    /**
     * Get nodeId
     *
     * @return integer
     */
    public function getNodeId()
    {
        return $this->nodeId;
    }

    /**
     * Set visitTime
     *
     * @param \DateTime $visitTime
     *
     * @return Hit
     */
    public function setVisitTime($visitTime)
    {
        $this->visitTime = $visitTime;

        return $this;
    }

    /**
     * Get visitTime
     *
     * @return \DateTime
     */
    public function getVisitTime()
    {
        return $this->visitTime;
    }

    /**
     * Set referingUrl
     *
     * @param string $referingUrl
     *
     * @return Hit
     */
    public function setReferingUrl($referingUrl)
    {
        $this->referingUrl = $referingUrl;

        return $this;
    }

    /**
     * Get referingUrl
     *
     * @return string
     */
    public function getReferingUrl()
    {
        return $this->referingUrl;
    }

    /**
     * Set hitRemoteAddr
     *
     * @param string $hitRemoteAddr
     *
     * @return Hit
     */
    public function setHitRemoteAddr($hitRemoteAddr)
    {
        $this->hitRemoteAddr = $hitRemoteAddr;

        return $this;
    }

    /**
     * Get hitRemoteAddr
     *
     * @return string
     */
    public function getHitRemoteAddr()
    {
        return $this->hitRemoteAddr;
    }

    /**
     * Set hitUserAgent
     *
     * @param string $hitUserAgent
     *
     * @return Hit
     */
    public function setHitUserAgent($hitUserAgent)
    {
        $this->hitUserAgent = $hitUserAgent;

        return $this;
    }

    /**
     * Get hitUserAgent
     *
     * @return string
     */
    public function getHitUserAgent()
    {
        return $this->hitUserAgent;
    }
}
