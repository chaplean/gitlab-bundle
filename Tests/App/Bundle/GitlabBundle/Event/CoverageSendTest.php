<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Event;

use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Event\CoverageSendEvent;
use PHPUnit\Framework\TestCase;

/**
 * CoverageSendTest.php.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageSendTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Event\CoverageSendEvent
     *
     * @return void
     */
    public function testCoverageSendEvent()
    {
        $coverage = new Coverage();
        $coverage2 = new Coverage();
        $event = new CoverageSendEvent($coverage, true);

        $this->assertEquals($coverage, $event->getCoverage());
        $this->assertTrue($event->isSendReport());

        $event->setCoverage($coverage2);
        $event->setSendReport(false);

        $this->assertEquals($coverage2, $event->getCoverage());
        $this->assertFalse($event->isSendReport());

        $this->assertEquals('coverage.send', CoverageSendEvent::NAME);
    }
}
