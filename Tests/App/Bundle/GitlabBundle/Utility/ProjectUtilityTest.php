<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Chaplean\Bundle\GitlabBundle\Model\ProjectModel;
use Chaplean\Bundle\GitlabBundle\Utility\ProjectUtility;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ProjectUtilityTest.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class ProjectUtilityTest extends MockeryTestCase
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
    private $projectRepository;

    /**
     * @var ProjectUtility
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
        $this->projectRepository = \Mockery::mock(EntityRepository::class);

        $this->registry->shouldReceive('getManager')->once()->andReturn($this->manager);
        $this->manager->shouldReceive('getRepository')->once()->with(Project::class)->andReturn($this->projectRepository);

        $this->utility = new ProjectUtility($this->registry);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\ProjectUtility::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(ProjectUtility::class, $this->utility);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\ProjectUtility::getFromProjectModel()
     *
     * @return void
     */
    public function testGetFromProjectModelWithExistingProject()
    {
        $project = \Mockery::mock(Project::class);
        $projectModel = \Mockery::mock(ProjectModel::class);

        $projectModel->shouldReceive('getGitlabId')->once()->andReturn(123);
        $this->projectRepository->shouldReceive('findOneByGitlabId')->once()->with(123)->andReturn($project);

        $result = $this->utility->getFromProjectModel($projectModel);

        $this->assertInstanceOf(Project::class, $result);
        $this->assertEquals($project, $result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\ProjectUtility::getFromProjectModel()
     *
     * @return void
     */
    public function testGetFromProjectModelWithNewProject()
    {
        $projectModel = \Mockery::mock(ProjectModel::class);

        $projectModel->shouldReceive('getGitlabId')->once()->andReturn(123);
        $this->projectRepository->shouldReceive('findOneByGitlabId')->once()->with(123)->andReturnNull();
        $projectModel->shouldReceive('getName')->once()->andReturn('project');

        $this->manager->shouldReceive('persist')->once()->andReturnNull();

        $result = $this->utility->getFromProjectModel($projectModel);

        $this->assertInstanceOf(Project::class, $result);
    }
}
