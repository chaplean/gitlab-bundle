<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Entity;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

/**
 * ProjectTest.php.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class ProjectTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::getId()
     *
     * @return void
     * @throws \ReflectionException
     */
    public function testId()
    {
        $project = new Project();

        $this->assertNull($project->getId());

        $reflectionClass = new \ReflectionClass(Project::class);
        $class = $reflectionClass->getProperty('id');
        $class->setAccessible(true);

        $class->setValue($project, 10);

        $this->assertEquals(10, $project->getId());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::getGitlabId()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::setGitlabId()
     *
     * @return void
     */
    public function testGitlabId()
    {
        $project = new Project();

        $this->assertNull($project->getGitlabId());

        $project->setGitlabId(125);
        $this->assertEquals(125, $project->getGitlabId());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::getName()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::setName()
     *
     * @return void
     */
    public function testName()
    {
        $project = new Project();

        $this->assertNull($project->getName());

        $project->setName('name');
        $this->assertEquals('name', $project->getName());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::getDateAdd()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::setDateAdd()
     *
     * @return void
     */
    public function testDateAdd()
    {
        $project = new Project();
        $date = new \DateTime();

        $this->assertNull($project->getDateAdd());

        $project->setDateAdd($date);
        $this->assertEquals($date, $project->getDateAdd());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::__construct()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::getBranches()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::addBranch()
     * @covers \Chaplean\Bundle\GitlabBundle\Entity\Project::removeBranch()
     *
     * @return void
     */
    public function testCoverage()
    {
        $project = new Project();
        $branch = new Branch();

        $this->assertInstanceOf(ArrayCollection::class, $project->getBranches());
        $this->assertCount(0, $project->getBranches());

        $project->addBranch($branch);
        $this->assertCount(1, $project->getBranches());

        $project->removeBranch($branch);
        $this->assertCount(0, $project->getBranches());
    }
}
