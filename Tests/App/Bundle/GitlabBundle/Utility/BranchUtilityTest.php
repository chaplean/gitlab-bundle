<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Chaplean\Bundle\GitlabBundle\Utility\BranchUtility;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class BranchUtilityTest.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class BranchUtilityTest extends MockeryTestCase
{
    /**
     * @var RegistryInterface|\Mockery\Mock
     */
    private $registry;

    /**
     * @var EntityManager|\Mockery\Mock
     */
    private $manager;

    /**
     * @var EntityRepository|\Mockery\Mock
     */
    private $branchRepository;

    /**
     * @var BranchUtility
     */
    private $utility;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->registry = \Mockery::mock(RegistryInterface::class);
        $this->manager = \Mockery::mock(EntityManager::class);
        $this->branchRepository = \Mockery::mock(EntityRepository::class);

        $this->registry->shouldReceive('getManager')->once()->andReturn($this->manager);
        $this->manager->shouldReceive('getRepository')->once()->with(Branch::class)->andReturn($this->branchRepository);

        $this->utility = new BranchUtility($this->registry);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\BranchUtility::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(BranchUtility::class, $this->utility);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\BranchUtility::getFromProjectAndName()
     *
     * @return void
     */
    public function testGetFromProjectAndNameWithExistingBranch()
    {
        $project = \Mockery::mock(Project::class);
        $branch = \Mockery::mock(Branch::class);

        $project->shouldReceive('getId')->once()->andReturn(1);
        $this->branchRepository->shouldReceive('findOneBy')->once()->with(['name' => 'name', 'project' => 1])->andReturn($branch);

        $result = $this->utility->getFromProjectAndName($project, 'name');

        $this->assertInstanceOf(Branch::class, $result);
        $this->assertEquals($branch, $result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\BranchUtility::getFromProjectAndName()
     *
     * @return void
     */
    public function testGetFromProjectAndNameWithNewBranch()
    {
        $project = \Mockery::mock(Project::class);

        $project->shouldReceive('getId')->once()->andReturn(1);
        $this->branchRepository->shouldReceive('findOneBy')->once()->with(['name' => 'name', 'project' => 1])->andReturnNull();
        $project->shouldReceive('addBranch')->once()->andReturnSelf();

        $this->manager->shouldReceive('persist')->once()->andReturnNull();

        $result = $this->utility->getFromProjectAndName($project, 'name');

        $this->assertInstanceOf(Branch::class, $result);
    }
}
