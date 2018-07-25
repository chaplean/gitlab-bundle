<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabClientBundle\Api\GitlabApi;
use Chaplean\Bundle\GitlabBundle\Model\PipelineModel;
use Chaplean\Bundle\GitlabBundle\Utility\PipelineUtility;
use Chaplean\Bundle\FormHandlerBundle\Form\FormHandler;
use Chaplean\Bundle\RestClientBundle\Api\Response\Failure\InvalidParameterResponse;
use Chaplean\Bundle\RestClientBundle\Api\Route;
use Mockery\Adapter\Phpunit\MockeryTestCase;

/**
 * Class PipelineUtilityTest.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class PipelineUtilityTest extends MockeryTestCase
{
    /**
     * @var GitlabApi|\Mockery\Mock
     */
    private $gitlabApi;

    /**
     * @var FormHandler|\Mockery\Mock
     */
    private $formHandler;

    /**
     * @var PipelineUtility
     */
    private $utility;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->gitlabApi = \Mockery::mock(GitlabApi::class);
        $this->formHandler = \Mockery::mock(FormHandler::class);

        $this->utility = new PipelineUtility($this->gitlabApi, $this->formHandler);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\PipelineUtility::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(PipelineUtility::class, $this->utility);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\PipelineUtility::getPipelineModel()
     *
     * @expectedException \RuntimeException
     *
     * @return void
     */
    public function testGetPipelineModelBadApiResponse()
    {
        $route = \Mockery::mock(Route::class);
        $response = \Mockery::mock(InvalidParameterResponse::class);

        $this->gitlabApi->shouldReceive('getPipeline')->once()->andReturn($route);
        $route->shouldReceive('bindUrlParameters')->once()->with(['project_id' => 10, 'pipeline_id' => 12])->andReturnSelf();
        $route->shouldReceive('exec')->once()->andReturn($response);
        $response->shouldReceive('succeeded')->once()->andReturnFalse();

        $this->utility->getPipelineModel(10, 12);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\PipelineUtility::getPipelineModel()
     *
     * @expectedException \RuntimeException
     *
     * @return void
     */
    public function testGetPipelineModelWithFormException()
    {
        $route = \Mockery::mock(Route::class);
        $response = \Mockery::mock(InvalidParameterResponse::class);

        $this->gitlabApi->shouldReceive('getPipeline')->once()->andReturn($route);
        $route->shouldReceive('bindUrlParameters')->once()->with(['project_id' => 10, 'pipeline_id' => 12])->andReturnSelf();
        $route->shouldReceive('exec')->once()->andReturn($response);
        $response->shouldReceive('succeeded')->once()->andReturnTrue();
        $response->shouldReceive('getContent')->once()->andReturn([]);

        $this->formHandler->shouldReceive('successHandler')->once()->andReturnSelf();
        $this->formHandler->shouldReceive('failureHandler')->once()->andReturnSelf();
        $this->formHandler->shouldReceive('handle')->once()->andThrows(new \RuntimeException());

        $this->utility->getPipelineModel(10, 12);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\PipelineUtility::getPipelineModel()
     *
     * @return void
     */
    public function testGetPipelineModel()
    {
        $route = \Mockery::mock(Route::class);
        $response = \Mockery::mock(InvalidParameterResponse::class);
        $pipelineModel = new PipelineModel();

        $this->gitlabApi->shouldReceive('getPipeline')->once()->andReturn($route);
        $route->shouldReceive('bindUrlParameters')->once()->with(['project_id' => 10, 'pipeline_id' => 12])->andReturnSelf();
        $route->shouldReceive('exec')->once()->andReturn($response);
        $response->shouldReceive('succeeded')->once()->andReturnTrue();
        $response->shouldReceive('getContent')->once()->andReturn([]);

        $this->formHandler->shouldReceive('successHandler')->once()->andReturnSelf();
        $this->formHandler->shouldReceive('failureHandler')->once()->andReturnSelf();
        $this->formHandler->shouldReceive('handle')->once()->andReturn($pipelineModel);

        $result = $this->utility->getPipelineModel(10, 12);

        $this->assertEquals($result, $pipelineModel);
    }
}
