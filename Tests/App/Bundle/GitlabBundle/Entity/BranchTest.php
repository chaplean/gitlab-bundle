<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Entity;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * BranchTest.php.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class BranchTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::getId()
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testId()
    {
        $branch = new Branch();

        $this->assertNull($branch->getId());

        $reflectionClass = new \ReflectionClass(Branch::class);
        $class = $reflectionClass->getProperty('id');
        $class->setAccessible(true);

        $class->setValue($branch, 10);

        $this->assertEquals(10, $branch->getId());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::getName()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::setName()
     *
     * @return void
     */
    public function testName()
    {
        $branch = new Branch();

        $this->assertNull($branch->getName());

        $branch->setName('name');
        $this->assertEquals('name', $branch->getName());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::getDateAdd()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::setDateAdd()
     *
     * @return void
     */
    public function testDateAdd()
    {
        $branch = new Branch();
        $date = new \DateTime();

        $this->assertNull($branch->getDateAdd());

        $branch->setDateAdd($date);
        $this->assertEquals($date, $branch->getDateAdd());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::getProject()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::setProject()
     *
     * @return void
     */
    public function testProject()
    {
        $branch = new Branch();
        $project = new Project();

        $this->assertNull($branch->getProject());

        $branch->setProject($project);
        $this->assertEquals($project, $branch->getProject());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::__construct()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::getCoverages()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::addCoverage()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Branch::removeCoverage()
     *
     * @return void
     */
    public function testCoverage()
    {
        $branch = new Branch();
        $coverage = new Coverage();

        $this->assertInstanceOf(ArrayCollection::class, $branch->getCoverages());
        $this->assertCount(0, $branch->getCoverages());

        $branch->addCoverage($coverage);
        $this->assertCount(1, $branch->getCoverages());

        $branch->removeCoverage($coverage);
        $this->assertCount(0, $branch->getCoverages());
    }
}
