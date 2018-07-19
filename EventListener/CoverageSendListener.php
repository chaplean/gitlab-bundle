<?php

namespace Chaplean\Bundle\GitlabBundle\EventListener;

use Chaplean\Bundle\GitlabBundle\Event\CoverageSendEvent;
use Chaplean\Bundle\GitlabBundle\Utility\CoverageNotificationUtility;

/**
 * Class CoverageSendListener.
 *
 * @package   Chaplean\Bundle\GitlabBundle\EventListener
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageSendListener
{
    /**
     * @var CoverageNotificationUtility
     */
    protected $coverageNotificationUtility;

    /**
     * CoverageSendListener constructor.
     *
     * @param CoverageNotificationUtility $coverageNotificationUtility
     */
    public function __construct(CoverageNotificationUtility $coverageNotificationUtility)
    {
        $this->coverageNotificationUtility = $coverageNotificationUtility;
    }

    /**
     * @param CoverageSendEvent $event
     *
     * @return void
     */
    public function onCoverageSend(CoverageSendEvent $event)
    {
        $this->coverageNotificationUtility->sendCoverageToSlack($event->getCoverage(), $event->isSendReport());
    }
}
