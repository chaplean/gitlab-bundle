<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabClientBundle\Api\GitlabApi;
use Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility;
use Chaplean\Bundle\RestClientBundle\Api\Response\Success\JsonResponse;
use Chaplean\Bundle\RestClientBundle\Api\Route;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class CoverageReportUtilityTest.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageReportUtilityTest extends MockeryTestCase
{
    /**
     * @var GitlabApi|\Mockery\Mock
     */
    private $gitlabApi;

    /**
     * @var LoggerInterface|\Mockery\Mock
     */
    private $logger;

    /**
     * @var CoverageReportUtility|\Mockery\Mock
     */
    private $utility;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->gitlabApi = \Mockery::mock(GitlabApi::class);
        $this->logger = \Mockery::mock(LoggerInterface::class);

        $this->utility = \Mockery::mock(
            CoverageReportUtility::class,
            [
                $this->gitlabApi,
                $this->logger,
                ['enable_coverage_report' => true, 'coverage_report_dir' => '/dir'],
                __DIR__ . '/../../../../Resources/',
                'coverages/'
            ]
        )->makePartial();
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        $fileSystem = new Filesystem();

        try {
            $fileSystem->remove(__DIR__ . '/../../../../Resources/coverages/abcd');
            $fileSystem->remove(__DIR__ . '/../../../../Resources/coverages/abcd.zip');
        } catch (\Exception $e) {}

    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(CoverageReportUtility::class, $this->utility);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::getCoverageReportContent()
     *
     * @return void
     */
    public function testGetCoverageReportContentNotSucceeded()
    {
        $route = \Mockery::mock(Route::class);
        $response = \Mockery::mock(JsonResponse::class);

        $this->gitlabApi->shouldReceive('getArtifacts')->once()->andReturn($route);
        $route->shouldReceive('bindUrlParameters')->once()->with(['project_id' => 10, 'job_id' => 12])->andReturnSelf();
        $route->shouldReceive('exec')->once()->andReturn($response);
        $response->shouldReceive('succeeded')->once()->andReturnFalse();

        $result = $this->utility->getCoverageReportContent(10, 12);

        $this->assertNull($result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::getCoverageReportContent()
     *
     * @return void
     */
    public function testGetCoverageReportContentSucceeded()
    {
        $route = \Mockery::mock(Route::class);
        $response = \Mockery::mock(JsonResponse::class);
        $data = ['a' => 'b'];

        $this->gitlabApi->shouldReceive('getArtifacts')->once()->andReturn($route);
        $route->shouldReceive('bindUrlParameters')->once()->with(['project_id' => 10, 'job_id' => 12])->andReturnSelf();
        $route->shouldReceive('exec')->once()->andReturn($response);
        $response->shouldReceive('succeeded')->once()->andReturnTrue();
        $response->shouldReceive('getContent')->once()->andReturn($data);

        $result = $this->utility->getCoverageReportContent(10, 12);

        $this->assertEquals($data, $result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::isCoverageReportExist()
     *
     * @return void
     */
    public function testIsCoverageReportExist()
    {
        $result = $this->utility->isCoverageReportExist('123456');

        $this->assertTrue($result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::isCoverageReportExist()
     *
     * @return void
     */
    public function testIsCoverageReportDirExistButNoCoverage()
    {
        $result = $this->utility->isCoverageReportExist('123456789');

        $this->assertFalse($result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::isCoverageReportExist()
     *
     * @return void
     */
    public function testIsCoverageReportNotExist()
    {
        $result = $this->utility->isCoverageReportExist('abcd');

        $this->assertFalse($result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::saveCoverageReport()
     *
     * @return void
     */
    public function testSaveCoverageReportInactive()
    {
        $this->utility = new CoverageReportUtility(
            $this->gitlabApi,
            $this->logger,
            ['enable_coverage_report' => false, 'coverage_report_dir' => '/coverage'],
            __DIR__ . '/../../../../Resources/',
            'coverages/'
        );

        $result = $this->utility->saveCoverageReport('abcd', 10);

        $this->assertFalse($result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::saveCoverageReport()
     *
     * @return void
     */
    public function testSaveCoverageReportAlreadyExist()
    {
        $this->utility->shouldReceive('isCoverageReportExist')->once()->with('abcd')->andReturnTrue();

        $result = $this->utility->saveCoverageReport('abcd', 10);

        $this->assertTrue($result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::saveCoverageReport()
     *
     * @return void
     */
    public function testSaveCoverageReportWithoutJobId()
    {
        $this->utility->shouldReceive('isCoverageReportExist')->once()->with('abcd')->andReturnFalse();

        $result = $this->utility->saveCoverageReport('abcd', 10);

        $this->assertFalse($result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::saveCoverageReport()
     *
     * @return void
     */
    public function testSaveCoverageReportWithBadContent()
    {
        $this->utility->shouldReceive('isCoverageReportExist')->once()->with('abcd')->andReturnFalse();
        $this->utility->shouldReceive('clearFile')->once()->andReturnNull();
        $this->utility->shouldReceive('getCoverageReportContent')->once()->withArgs([10, 125])->andReturnNull();

        $result = $this->utility->saveCoverageReport('abcd', 10, 125);

        $this->assertFalse($result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility::saveCoverageReport()
     *
     * @return void
     */
    public function testSaveCoverageReport()
    {
        $this->logger->shouldReceive('error')->once();
        $this->utility->shouldReceive('isCoverageReportExist')->twice()->with('abcd')->andReturnFalse();
        $this->utility->shouldReceive('clearFile')->times(3)->andReturnNull();
        $this->utility->shouldReceive('getCoverageReportContent')->once()->withArgs([10, 125])->andReturn('content');

        $result = $this->utility->saveCoverageReport('abcd', 10, 125);

        $this->assertFalse($result);
    }
}
