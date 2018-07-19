<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\EventListener;

use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Event\CoverageSendEvent;
use Chaplean\Bundle\GitlabBundle\EventListener\CoverageSendListener;
use Chaplean\Bundle\GitlabBundle\Utility\CoverageNotificationUtility;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * CoverageSendListenerTest.php.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageSendListenerTest extends MockeryTestCase
{
    /**
     * @var CoverageNotificationUtility|\Mockery\Mock
     */
    private $coverageNotificationUtility;

    /**
     * @var CoverageSendListener
     */
    private $listener;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->coverageNotificationUtility = \Mockery::mock(CoverageNotificationUtility::class);

        $this->listener = new CoverageSendListener($this->coverageNotificationUtility);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\EventListener\CoverageSendListener::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(CoverageSendListener::class, $this->listener);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\EventListener\CoverageSendListener::onCoverageSend()
     *
     * @return void
     */
    public function testOnCoverageSend()
    {
        $event = \Mockery::mock(CoverageSendEvent::class);
        $coverage = \Mockery::mock(Coverage::class);

        $event->shouldReceive('getCoverage')->once()->andReturn($coverage);
        $event->shouldReceive('isSendReport')->once()->andReturnFalse();

        $this->coverageNotificationUtility->shouldReceive('sendCoverageToSlack')->once()->withArgs([$coverage, false])->andReturnNull();

        $this->listener->onCoverageSend($event);
    }
}
