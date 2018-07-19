<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Form\Handler;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Chaplean\Bundle\GitlabBundle\Form\Handler\WebhookPipelineSuccessHandler;
use Chaplean\Bundle\GitlabBundle\Model\PipelineModel;
use Chaplean\Bundle\GitlabBundle\Model\ProjectModel;
use Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel;
use Chaplean\Bundle\GitlabBundle\Utility\BranchUtility;
use Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility;
use Chaplean\Bundle\GitlabBundle\Utility\CoverageUtility;
use Chaplean\Bundle\GitlabBundle\Utility\PipelineUtility;
use Chaplean\Bundle\GitlabBundle\Utility\ProjectUtility;
use Doctrine\ORM\EntityManager;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class WebhookPipelineSuccessHandlerTest.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class WebhookPipelineSuccessHandlerTest extends MockeryTestCase
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
     * @var PipelineUtility|\Mockery\Mock
     */
    private $pipelineUtility;

    /**
     * @var ProjectUtility|\Mockery\Mock
     */
    private $projectUtility;

    /**
     * @var BranchUtility|\Mockery\Mock
     */
    private $branchUtility;

    /**
     * @var CoverageUtility|\Mockery\Mock
     */
    private $coverageUtility;

    /**
     * @var CoverageReportUtility|\Mockery\Mock
     */
    private $coverageReportUtility;

    /**
     * @var EventDispatcherInterface|\Mockery\Mock
     */
    private $eventDispatcherInterface;

    /**
     * @var WebhookPipelineSuccessHandler
     */
    private $handler;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->registry = \Mockery::mock(RegistryInterface::class);
        $this->manager = \Mockery::mock(EntityManager::class);
        $this->pipelineUtility = \Mockery::mock(PipelineUtility::class);
        $this->projectUtility = \Mockery::mock(ProjectUtility::class);
        $this->branchUtility = \Mockery::mock(BranchUtility::class);
        $this->coverageUtility = \Mockery::mock(CoverageUtility::class);
        $this->coverageReportUtility = \Mockery::mock(CoverageReportUtility::class);
        $this->eventDispatcherInterface = \Mockery::mock(EventDispatcherInterface::class);

        $this->registry->shouldReceive('getManager')->once()->andReturn($this->manager);

        $this->handler = new WebhookPipelineSuccessHandler(
            $this->registry,
            $this->pipelineUtility,
            $this->projectUtility,
            $this->branchUtility,
            $this->coverageUtility,
            $this->coverageReportUtility,
            $this->eventDispatcherInterface
        );
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Form\Handler\WebhookPipelineSuccessHandler::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(WebhookPipelineSuccessHandler::class, $this->handler);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Form\Handler\WebhookPipelineSuccessHandler::onSuccess()
     *
     * @return void
     */
    public function testOnSuccessWithoutSucceededPipeline()
    {
        $model = \Mockery::mock(WebhookPipelineEventModel::class);

        $model->shouldReceive('hasPipelineSucceeded')->once()->andReturnFalse();

        $result = $this->handler->onSuccess($model, []);

        $this->assertEquals(['answer' => 'ignored'], $result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Form\Handler\WebhookPipelineSuccessHandler::onSuccess()
     *
     * @return void
     */
    public function testOnSuccessWithoutCoverageApiException()
    {
        $model = \Mockery::mock(WebhookPipelineEventModel::class);
        $projectModel = \Mockery::mock(ProjectModel::class);
        $pipelineModel = \Mockery::mock(PipelineModel::class);
        $project = \Mockery::mock(Project::class);
        $branch = \Mockery::mock(Branch::class);

        $model->shouldReceive('hasPipelineSucceeded')->once()->andReturnTrue();
        $model->shouldReceive('getProject')->once()->andReturn($projectModel);
        $model->shouldReceive('getObjectAttributes')->once()->andReturn($pipelineModel);
        $model->shouldReceive('getPipelineId')->once()->andReturn(10);
        $pipelineModel->shouldReceive('getBranch')->once()->andReturn('feature/t12345');
        $pipelineModel->shouldReceive('getCoverage')->once()->andReturn(0);
        $projectModel->shouldReceive('getGitlabId')->once()->andReturn(125);

        $this->projectUtility->shouldReceive('getFromProjectModel')->once()->with($projectModel)->andReturn($project);
        $this->branchUtility->shouldReceive('getFromProjectAndName')->once()->withArgs([$project, 'feature/t12345'])->andReturn($branch);
        $this->pipelineUtility->shouldReceive('getPipelineModel')->once()->withArgs([125, 10])->andThrows(new \Exception());

        $result = $this->handler->onSuccess($model, []);

        $this->assertEquals(['answer' => 'failed'], $result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Form\Handler\WebhookPipelineSuccessHandler::onSuccess()
     *
     * @return void
     */
    public function testOnSuccessWithoutCoverageWithApi()
    {
        $model = \Mockery::mock(WebhookPipelineEventModel::class);
        $projectModel = \Mockery::mock(ProjectModel::class);
        $pipelineModel = \Mockery::mock(PipelineModel::class);
        $project = \Mockery::mock(Project::class);
        $branch = \Mockery::mock(Branch::class);

        $model->shouldReceive('hasPipelineSucceeded')->once()->andReturnTrue();
        $model->shouldReceive('getProject')->once()->andReturn($projectModel);
        $model->shouldReceive('getObjectAttributes')->once()->andReturn($pipelineModel);
        $model->shouldReceive('getPipelineId')->once()->andReturn(10);
        $pipelineModel->shouldReceive('getBranch')->once()->andReturn('feature/t12345');
        $pipelineModel->shouldReceive('getCoverage')->twice()->andReturn(0);
        $pipelineModel->shouldReceive('getSha')->once()->andReturn('ab65sd2156');
        $projectModel->shouldReceive('getGitlabId')->once()->andReturn(125);

        $this->projectUtility->shouldReceive('getFromProjectModel')->once()->with($projectModel)->andReturn($project);
        $this->branchUtility->shouldReceive('getFromProjectAndName')->once()->withArgs([$project, 'feature/t12345'])->andReturn($branch);
        $this->pipelineUtility->shouldReceive('getPipelineModel')->once()->withArgs([125, 10])->andReturn($pipelineModel);
        $this->coverageUtility->shouldReceive('getCoveragePercentWithSameSha')->once()->withArgs([$project, 'ab65sd2156'])->andReturn(0);

        $result = $this->handler->onSuccess($model, []);

        $this->assertEquals(['answer' => 'failed'], $result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Form\Handler\WebhookPipelineSuccessHandler::onSuccess()
     *
     * @return void
     */
    public function testOnSuccess()
    {
        $model = \Mockery::mock(WebhookPipelineEventModel::class);
        $projectModel = \Mockery::mock(ProjectModel::class);
        $pipelineModel = \Mockery::mock(PipelineModel::class);
        $project = \Mockery::mock(Project::class);
        $coverage = \Mockery::mock(Coverage::class);
        $branch = new Branch();

        $model->shouldReceive('hasPipelineSucceeded')->once()->andReturnTrue();
        $model->shouldReceive('getProject')->once()->andReturn($projectModel);
        $model->shouldReceive('getObjectAttributes')->once()->andReturn($pipelineModel);
        $model->shouldReceive('getLastSucceededTestBuildId')->once()->andReturnNull();
        $pipelineModel->shouldReceive('getBranch')->once()->andReturn('feature/t12345');
        $pipelineModel->shouldReceive('getCoverage')->once()->andReturn(50.25);
        $pipelineModel->shouldReceive('getSha')->once()->andReturn('ab65sd2156');
        $project->shouldReceive('getGitlabId')->once()->andReturn(1025);

        $this->projectUtility->shouldReceive('getFromProjectModel')->once()->with($projectModel)->andReturn($project);
        $this->branchUtility->shouldReceive('getFromProjectAndName')->once()->withArgs([$project, 'feature/t12345'])->andReturn($branch);
        $this->coverageUtility->shouldReceive('create')->once()->withArgs([$branch, 'ab65sd2156', 0.5025])->andReturn($coverage);
        $this->coverageReportUtility->shouldReceive('saveCoverageReport')->once()->withArgs(['ab65sd2156', 1025, null])->andReturnTrue();

        $this->eventDispatcherInterface->shouldReceive('dispatch')->once()->andReturnNull();

        $result = $this->handler->onSuccess($model, []);

        $this->assertEquals(['answer' => 'acknowledged'], $result);
    }
}
