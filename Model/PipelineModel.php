<?php

namespace Chaplean\Bundle\GitlabBundle\Model;

/**
 * Class PipelineModel.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Model
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class PipelineModel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $branch;

    /**
     * @var string
     */
    private $sha;

    /**
     * @var string
     */
    private $coverage;

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
     * @return PipelineModel
     */
    public function setId($id)
    {
        $this->id = $id;

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
     * @return PipelineModel
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get branch.
     *
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Set branch.
     *
     * @param string $branch
     *
     * @return PipelineModel
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get sha.
     *
     * @return string
     */
    public function getSha()
    {
        return $this->sha;
    }

    /**
     * Set sha.
     *
     * @param string $sha
     *
     * @return PipelineModel
     */
    public function setSha($sha)
    {
        $this->sha = $sha;

        return $this;
    }

    /**
     * Get coverage.
     *
     * @return string
     */
    public function getCoverage()
    {
        return $this->coverage;
    }

    /**
     * Set coverage.
     *
     * @param string $coverage
     *
     * @return PipelineModel
     */
    public function setCoverage($coverage)
    {
        $this->coverage = $coverage;

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
