<?php

namespace Chaplean\Bundle\GitlabBundle\Model;

/**
 * Class BuildModel.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Model
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class BuildModel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $stage;

    /**
     * @var string
     */
    private $status;

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param integer $id
     *
     * @return BuildModel
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get stage.
     *
     * @return string
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * Set stage.
     *
     * @param string $stage
     *
     * @return BuildModel
     */
    public function setStage($stage)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return BuildModel
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->getStatus() === 'success';
    }
}
