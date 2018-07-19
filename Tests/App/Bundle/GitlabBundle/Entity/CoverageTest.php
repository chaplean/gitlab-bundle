<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Entity;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use PHPUnit\Framework\TestCase;

/**
 * CoverageTest.php.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Coverage::getId()
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testId()
    {
        $coverage = new Coverage();

        $this->assertNull($coverage->getId());

        $reflectionClass = new \ReflectionClass(Coverage::class);
        $class = $reflectionClass->getProperty('id');
        $class->setAccessible(true);

        $class->setValue($coverage, 10);

        $this->assertEquals(10, $coverage->getId());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Coverage::getPercentCovered()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Coverage::setPercentCovered()
     *
     * @return void
     */
    public function testPercentCovered()
    {
        $coverage = new Coverage();

        $this->assertNull($coverage->getPercentCovered());

        $coverage->setPercentCovered(0.5);
        $this->assertEquals(0.5, $coverage->getPercentCovered());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Coverage::getSha()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Coverage::setSha()
     *
     * @return void
     */
    public function testSha()
    {
        $coverage = new Coverage();

        $this->assertNull($coverage->getSha());

        $coverage->setSha('sha');
        $this->assertEquals('sha', $coverage->getSha());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Coverage::getDateAdd()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Coverage::setDateAdd()
     *
     * @return void
     */
    public function testDateAdd()
    {
        $coverage = new Coverage();
        $date = new \DateTime();

        $this->assertNull($coverage->getDateAdd());

        $coverage->setDateAdd($date);
        $this->assertEquals($date, $coverage->getDateAdd());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Coverage::getBranch()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Coverage::setBranch()
     *
     * @return void
     */
    public function testProject()
    {
        $coverage = new Coverage();
        $branch = new Branch();

        $this->assertNull($coverage->getBranch());

        $coverage->setBranch($branch);
        $this->assertEquals($branch, $coverage->getBranch());
    }
}
