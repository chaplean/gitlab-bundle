<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Model;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Chaplean\Bundle\GitlabBundle\Model\SlackCoverageNotificationModel;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class SlackCoverageNotificationModelTest.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Model
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class SlackCoverageNotificationModelTest extends MockeryTestCase
{
    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Model\SlackCoverageNotificationModel
     *
     * @return void
     */
    public function testConstructWithIncrease()
    {
        $branch = \Mockery::mock(Branch::class);
        $project = \Mockery::mock(Project::class);
        $coverageTo = \Mockery::mock(Coverage::class);
        $coverageFrom = \Mockery::mock(Coverage::class);

        $coverageTo->shouldReceive('getBranch')->once()->andReturn($branch);
        $branch->shouldReceive('getProject')->once()->andReturn($project);
        $coverageTo->shouldReceive('getPercentCovered')->once()->andReturn(0.5);
        $coverageFrom->shouldReceive('getPercentCovered')->once()->andReturn(0.4);

        /** @var SlackCoverageNotificationModel $model */
        $model = \Mockery::mock(SlackCoverageNotificationModel::class, [$coverageTo, $coverageFrom, ''])->makePartial();

        $this->assertInstanceOf(SlackCoverageNotificationModel::class, $model);
        $this->assertEquals($branch, $model->getBranch());
        $this->assertEquals($project, $model->getProject());
        $this->assertEquals($coverageTo, $model->getCoverageTo());
        $this->assertEquals($coverageFrom, $model->getCoverageFrom());
        $this->assertEquals(0.5, $model->getCoverageToValue());
        $this->assertEquals(0.4, $model->getCoverageFromValue());
        $this->assertEquals(1, $model->getStatus());
        $this->assertEquals('#36A64F', $model->getColor());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Model\SlackCoverageNotificationModel
     *
     * @return void
     */
    public function testConstructWithDecrease()
    {
        $branch = \Mockery::mock(Branch::class);
        $project = \Mockery::mock(Project::class);
        $coverageTo = \Mockery::mock(Coverage::class);
        $coverageFrom = \Mockery::mock(Coverage::class);

        $coverageTo->shouldReceive('getBranch')->once()->andReturn($branch);
        $branch->shouldReceive('getProject')->once()->andReturn($project);
        $coverageTo->shouldReceive('getPercentCovered')->once()->andReturn(0.4);
        $coverageFrom->shouldReceive('getPercentCovered')->once()->andReturn(0.5);

        /** @var SlackCoverageNotificationModel $model */
        $model = \Mockery::mock(SlackCoverageNotificationModel::class, [$coverageTo, $coverageFrom, ''])->makePartial();

        $this->assertInstanceOf(SlackCoverageNotificationModel::class, $model);
        $this->assertEquals($branch, $model->getBranch());
        $this->assertEquals($project, $model->getProject());
        $this->assertEquals($coverageTo, $model->getCoverageTo());
        $this->assertEquals($coverageFrom, $model->getCoverageFrom());
        $this->assertEquals(0.4, $model->getCoverageToValue());
        $this->assertEquals(0.5, $model->getCoverageFromValue());
        $this->assertEquals(-1, $model->getStatus());
        $this->assertEquals('#D00000', $model->getColor());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Model\SlackCoverageNotificationModel
     *
     * @return void
     */
    public function testConstructWithSameCoverage()
    {
        $branch = \Mockery::mock(Branch::class);
        $project = \Mockery::mock(Project::class);
        $coverageTo = \Mockery::mock(Coverage::class);
        $coverageFrom = \Mockery::mock(Coverage::class);

        $coverageTo->shouldReceive('getBranch')->once()->andReturn($branch);
        $branch->shouldReceive('getProject')->once()->andReturn($project);
        $coverageTo->shouldReceive('getPercentCovered')->once()->andReturn(0.5);
        $coverageFrom->shouldReceive('getPercentCovered')->once()->andReturn(0.5);

        /** @var SlackCoverageNotificationModel $model */
        $model = \Mockery::mock(SlackCoverageNotificationModel::class, [$coverageTo, $coverageFrom, ''])->makePartial();

        $this->assertInstanceOf(SlackCoverageNotificationModel::class, $model);
        $this->assertEquals($branch, $model->getBranch());
        $this->assertEquals($project, $model->getProject());
        $this->assertEquals($coverageTo, $model->getCoverageTo());
        $this->assertEquals($coverageFrom, $model->getCoverageFrom());
        $this->assertEquals(0.5, $model->getCoverageToValue());
        $this->assertEquals(0.5, $model->getCoverageFromValue());
        $this->assertEquals(0, $model->getStatus());
        $this->assertEquals('#36A64F', $model->getColor());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Model\SlackCoverageNotificationModel::getText()
     *
     * @return void
     */
    public function testGetTextWithIncrease()
    {
        $branch = \Mockery::mock(Branch::class);
        $project = \Mockery::mock(Project::class);
        $coverageTo = \Mockery::mock(Coverage::class);
        $coverageFrom = \Mockery::mock(Coverage::class);

        $coverageTo->shouldReceive('getBranch')->once()->andReturn($branch);
        $branch->shouldReceive('getProject')->once()->andReturn($project);
        $coverageTo->shouldReceive('getPercentCovered')->once()->andReturn(0.5);
        $coverageFrom->shouldReceive('getPercentCovered')->once()->andReturn(0.4);
        $branch->shouldReceive('getName')->once()->andReturn('feature/t12345');
        $project->shouldReceive('getName')->once()->andReturn('project_name');

        /** @var SlackCoverageNotificationModel $model */
        $model = \Mockery::mock(SlackCoverageNotificationModel::class, [$coverageTo, $coverageFrom, ''])->makePartial();

        $this->assertEquals('Coverage increased on *chaplean/project_name* _feature/t12345_ from *40.00%* to *50.00%*', $model->getText());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Model\SlackCoverageNotificationModel::getText()
     *
     * @return void
     */
    public function testGetTextWithDecrease()
    {
        $branch = \Mockery::mock(Branch::class);
        $project = \Mockery::mock(Project::class);
        $coverageTo = \Mockery::mock(Coverage::class);
        $coverageFrom = \Mockery::mock(Coverage::class);

        $coverageTo->shouldReceive('getBranch')->once()->andReturn($branch);
        $branch->shouldReceive('getProject')->once()->andReturn($project);
        $coverageTo->shouldReceive('getPercentCovered')->once()->andReturn(0.4);
        $coverageFrom->shouldReceive('getPercentCovered')->once()->andReturn(0.5);
        $branch->shouldReceive('getName')->once()->andReturn('feature/t12345');
        $project->shouldReceive('getName')->once()->andReturn('project_name');

        /** @var SlackCoverageNotificationModel $model */
        $model = \Mockery::mock(SlackCoverageNotificationModel::class, [$coverageTo, $coverageFrom, ''])->makePartial();

        $this->assertEquals('Coverage decreased on *chaplean/project_name* _feature/t12345_ from *50.00%* to *40.00%*', $model->getText());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Model\SlackCoverageNotificationModel::getText()
     *
     * @return void
     */
    public function testGetTextWithSameCoverage()
    {
        $branch = \Mockery::mock(Branch::class);
        $project = \Mockery::mock(Project::class);
        $coverageTo = \Mockery::mock(Coverage::class);
        $coverageFrom = \Mockery::mock(Coverage::class);

        $coverageTo->shouldReceive('getBranch')->once()->andReturn($branch);
        $branch->shouldReceive('getProject')->once()->andReturn($project);
        $coverageTo->shouldReceive('getPercentCovered')->once()->andReturn(0.5);
        $coverageFrom->shouldReceive('getPercentCovered')->once()->andReturn(0.5);
        $branch->shouldReceive('getName')->once()->andReturn('feature/t12345');
        $project->shouldReceive('getName')->once()->andReturn('project_name');

        /** @var SlackCoverageNotificationModel $model */
        $model = \Mockery::mock(SlackCoverageNotificationModel::class, [$coverageTo, $coverageFrom, ''])->makePartial();

        $this->assertEquals('Coverage remained the same on *chaplean/project_name* _feature/t12345_ from *50.00%* to *50.00%*', $model->getText());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Model\SlackCoverageNotificationModel::getNotificationData()
     *
     * @return void
     */
    public function testGetNotificationData()
    {
        $branch = \Mockery::mock(Branch::class);
        $project = \Mockery::mock(Project::class);
        $coverageTo = \Mockery::mock(Coverage::class);
        $coverageFrom = \Mockery::mock(Coverage::class);

        $coverageTo->shouldReceive('getBranch')->once()->andReturn($branch);
        $branch->shouldReceive('getProject')->once()->andReturn($project);
        $coverageTo->shouldReceive('getPercentCovered')->once()->andReturn(0.5);
        $coverageFrom->shouldReceive('getPercentCovered')->once()->andReturn(0.4);
        $branch->shouldReceive('getName')->twice()->andReturn('feature/t12345');
        $project->shouldReceive('getName')->once()->andReturn('project_name');

        /** @var SlackCoverageNotificationModel $model */
        $model = \Mockery::mock(SlackCoverageNotificationModel::class, [$coverageTo, $coverageFrom, ''])->makePartial();

        $this->assertEquals(
            [
                'text'        => 'Coverage increased on *chaplean/project_name* _feature/t12345_ from *40.00%* to *50.00%*',
                'attachments' => [
                    [
                        'fields' => [
                            [
                                'title' => 'Change',
                                'value' => '10.00%',
                                'short' => true
                            ],
                            [
                                'title' => 'Commit on "feature/t12345"',
                                'value' => '',
                                'short' => true
                            ]
                        ],
                        'color' => '#36A64F'
                    ]
                ]
            ],
            $model->getNotificationData()
        );
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Model\SlackCoverageNotificationModel::getNotificationData()
     *
     * @return void
     */
    public function testGetNotificationDataWithReport()
    {
        $branch = \Mockery::mock(Branch::class);
        $project = \Mockery::mock(Project::class);
        $coverageTo = \Mockery::mock(Coverage::class);
        $coverageFrom = \Mockery::mock(Coverage::class);

        $coverageTo->shouldReceive('getBranch')->once()->andReturn($branch);
        $branch->shouldReceive('getProject')->once()->andReturn($project);
        $coverageTo->shouldReceive('getPercentCovered')->once()->andReturn(0.5);
        $coverageFrom->shouldReceive('getPercentCovered')->once()->andReturn(0.4);
        $branch->shouldReceive('getName')->twice()->andReturn('feature/t12345');
        $project->shouldReceive('getName')->once()->andReturn('project_name');

        /** @var SlackCoverageNotificationModel $model */
        $model = \Mockery::mock(SlackCoverageNotificationModel::class, [$coverageTo, $coverageFrom, 'www.chaplean.coop'])->makePartial();

        $this->assertEquals(
            [
                'text'        => 'Coverage increased on *chaplean/project_name* _feature/t12345_ from *40.00%* to *50.00%*',
                'attachments' => [
                    [
                        'fields' => [
                            [
                                'title' => 'Change',
                                'value' => '10.00%',
                                'short' => true
                            ],
                            [
                                'title' => 'Commit on "feature/t12345"',
                                'value' => 'www.chaplean.coop',
                                'short' => true
                            ]
                        ],
                        'color' => '#36A64F'
                    ]
                ]
            ],
            $model->getNotificationData()
        );
    }
}
