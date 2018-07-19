<?php

namespace Chaplean\Bundle\GitlabBundle\Model;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Entity\Project;

/**
 * Class SlackCoverageNotificationModel.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Model
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class SlackCoverageNotificationModel
{
    /**
     * @var Coverage|null
     */
    private $coverageFrom;

    /**
     * @var Coverage
     */
    private $coverageTo;

    /**
     * @var string
     */
    private $reportUrl;

    /**
     * @var Project
     */
    private $project;

    /**
     * @var Branch
     */
    private $branch;

    /**
     * @var float
     */
    private $coverageFromValue;

    /**
     * @var float
     */
    private $coverageToValue;

    /**
     *  0 if coverage remained
     *  1 if coverage increased
     * -1 if coverage decreased
     *
     * @var integer
     */
    private $status;

    /**
     * @param Coverage      $coverageTo
     * @param Coverage|null $coverageFrom
     * @param string        $reportUrl
     *
     * SlackCoverageNotificationModel constructor.
     */
    public function __construct(Coverage $coverageTo, Coverage $coverageFrom = null, $reportUrl)
    {
        $this->coverageTo = $coverageTo;
        $this->coverageFrom = $coverageFrom;
        $this->reportUrl = $reportUrl;
        $this->branch = $coverageTo->getBranch();
        $this->project = $this->branch->getProject();

        $this->coverageToValue = $this->getCoverageValue($coverageTo);
        $this->coverageFromValue = $this->getCoverageValue($coverageFrom);

        $this->initStatus();
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @return Coverage
     */
    public function getCoverageFrom()
    {
        return $this->coverageFrom;
    }

    /**
     * @return Coverage
     */
    public function getCoverageTo()
    {
        return $this->coverageTo;
    }

    /**
     * @return float
     */
    public function getCoverageFromValue()
    {
        return $this->coverageFromValue;
    }

    /**
     * @return float
     */
    public function getCoverageToValue()
    {
        return $this->coverageToValue;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return void
     */
    private function initStatus()
    {
        if ($this->coverageFromValue == $this->coverageToValue) {
            $this->status = 0;
        } elseif ($this->coverageFromValue > $this->coverageToValue) {
            $this->status = -1;
        } else {
            $this->status = 1;
        }
    }

    /**
     * @param Coverage|null $coverage
     *
     * @return float
     */
    private function getCoverageValue(Coverage $coverage = null)
    {
        $value = 0;

        if ($coverage !== null) {
            $value = $coverage->getPercentCovered();
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        switch ($this->status) {
            case -1:
                return '#D00000';
            default:
                return '#36A64F';
        }
    }

    /**
     * @return string
     */
    public function getText()
    {

        switch ($this->status) {
            case -1:
                $status = 'decreased';
                break;
            case 1:
                $status = 'increased';
                break;
            default:
                $status = 'remained the same';
                break;
        }

        return sprintf(
            'Coverage %s on *chaplean/%s* _%s_ from *%0.2f%%* to *%0.2f%%*',
            $status,
            $this->project->getName(),
            $this->branch->getName(),
            $this->coverageFromValue * 100,
            $this->coverageToValue * 100
        );
    }

    /**
     * @return array
     */
    public function getNotificationData()
    {
        $coverageChange = $this->coverageToValue - $this->coverageFromValue;

        return [
            'text'        => $this->getText(),
            'attachments' => [
                [
                    'fields' => [
                        [
                            'title' => 'Change',
                            'value' => sprintf('%0.2f%%', $coverageChange * 100.00),
                            'short' => true
                        ],
                        [
                            'title' => 'Commit on "' . $this->branch->getName() . '"',
                            'value' => $this->reportUrl,
                            'short' => true
                        ]
                    ],
                    'color'  => $this->getColor()
                ]
            ]
        ];
    }
}
