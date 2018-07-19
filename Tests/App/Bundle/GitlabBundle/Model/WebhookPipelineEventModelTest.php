<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Model;

use Chaplean\Bundle\GitlabBundle\Model\BuildModel;
use Chaplean\Bundle\GitlabBundle\Model\PipelineModel;
use Chaplean\Bundle\GitlabBundle\Model\ProjectModel;
use Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * Class WebhookPipelineEventModelTest.
 *
 * @package   Tests\Chaplean\Bundle\GitlabBundle\Model
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class WebhookPipelineEventModelTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $model = new WebhookPipelineEventModel();

        $this->assertInstanceOf(ArrayCollection::class, $model->getBuilds());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::getObjectKind()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::setObjectKind()
     *
     * @return void
     */
    public function testObjectKind()
    {
        $model = new WebhookPipelineEventModel();

        $this->assertNull($model->getObjectKind());

        $model->setObjectKind('objectKind');
        $this->assertEquals('objectKind', $model->getObjectKind());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::getObjectAttributes()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::setObjectAttributes()
     *
     * @return void
     */
    public function testObjectAttributes()
    {
        $model = new WebhookPipelineEventModel();
        $attributes = new PipelineModel();

        $this->assertNull($model->getObjectAttributes());

        $model->setObjectAttributes($attributes);
        $this->assertEquals($attributes, $model->getObjectAttributes());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::getProject()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::setProject()
     *
     * @return void
     */
    public function testProject()
    {
        $model = new WebhookPipelineEventModel();
        $project = new ProjectModel();

        $this->assertNull($model->getProject());

        $model->setProject($project);
        $this->assertEquals($project, $model->getProject());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::getBuilds()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::addBuild()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::removeBuild()
     *
     * @return void
     */
    public function testBuilds()
    {
        $model = new WebhookPipelineEventModel();
        $build = new BuildModel();

        $this->assertInstanceOf(ArrayCollection::class, $model->getBuilds());
        $this->assertCount(0, $model->getBuilds());

        $model->addBuild($build);
        $this->assertCount(1, $model->getBuilds());

        $model->removeBuild($build);
        $this->assertCount(0, $model->getBuilds());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::getPipelineId()
     *
     * @return void
     */
    public function testGetPipelineId()
    {
        $model = new WebhookPipelineEventModel();
        $attributes = new PipelineModel();
        $model->setObjectAttributes($attributes);

        $attributes->setId(42);

        $this->assertEquals($attributes->getId(), $model->getPipelineId());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::hasPipelineSucceeded()
     *
     * @return void
     */
    public function testHasPipelineSucceeded()
    {
        $model = new WebhookPipelineEventModel();
        $attributes = new PipelineModel();
        $model->setObjectAttributes($attributes);

        $attributes->setStatus('answer');

        $this->assertEquals($attributes->isSuccess(), $model->hasPipelineSucceeded());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::getLastSucceededTestBuildId()
     *
     * @return void
     */
    public function testGetLastSucceededTestBuildIdWithoutBuild()
    {
        $model = new WebhookPipelineEventModel();

        $result = $model->getLastSucceededTestBuildId();

        $this->assertNull($result);
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel::getLastSucceededTestBuildId()
     *
     * @return void
     */
    public function testGetLastSucceededTest()
    {
        $model = new WebhookPipelineEventModel();
        $build = \Mockery::mock(BuildModel::class);
        $build2 = \Mockery::mock(BuildModel::class);

        $model->addBuild($build);
        $model->addBuild($build2);

        $build->shouldReceive('isSuccess')->once()->andReturnTrue();
        $build2->shouldReceive('isSuccess')->once()->andReturnTrue();
        $build->shouldReceive('getStage')->once()->andReturn('test');
        $build2->shouldReceive('getStage')->once()->andReturn('test');
        $build->shouldReceive('getId')->once()->andReturn(12);
        $build2->shouldReceive('getId')->once()->andReturn(18);

        $result = $model->getLastSucceededTestBuildId();

        $this->assertEquals(18, $result);
    }
}
