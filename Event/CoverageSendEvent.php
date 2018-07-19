<?php

namespace Chaplean\Bundle\GitlabBundle\Event;

use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class SendCoverageEvent.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Event
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageSendEvent extends Event
{
    const NAME = 'coverage.send';

    /**
     * @var Coverage
     */
    protected $coverage;

    /**
     * @var boolean
     */
    protected $sendReport;

    /**
     * SendCoverageEvent constructor.
     *
     * @param Coverage $coverage
     * @param boolean  $sendReport
     */
    public function __construct(Coverage $coverage, $sendReport)
    {
        $this->coverage = $coverage;
        $this->sendReport = $sendReport;
    }

    /**
     * Get coverage.
     *
     * @return Coverage
     */
    public function getCoverage()
    {
        return $this->coverage;
    }

    /**
     * Set coverage.
     *
     * @param Coverage $coverage
     *
     * @return self
     */
    public function setCoverage(Coverage $coverage)
    {
        $this->coverage = $coverage;

        return $this;
    }

    /**
     * Get sendReport.
     *
     * @return boolean
     */
    public function isSendReport()
    {
        return $this->sendReport;
    }

    /**
     * Set sendReport.
     *
     * @param boolean $sendReport
     *
     * @return self
     */
    public function setSendReport($sendReport)
    {
        $this->sendReport = $sendReport;

        return $this;
    }
}
