<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Chaplean\Bundle\GitlabBundle\Utility\CoverageNotificationUtility;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use GuzzleHttp\Client;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Router;

/**
 * Class CoverageNotificationUtilityTest.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageNotificationUtilityTest extends MockeryTestCase
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
     * @var Client|\Mockery\Mock
     */
    private $client;

    /**
     * @var Router|\Mockery\Mock
     */
    private $router;

    /**
     * @var CoverageNotificationUtility
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
        $this->client = \Mockery::mock(Client::class);
        $this->router = \Mockery::mock(Router::class);

        $this->registry->shouldReceive('getManager')->once()->andReturn($this->manager);

        $this->utility = new CoverageNotificationUtility(
            $this->registry,
            $this->client,
            $this->router,
            'host',
            'url',
            ['enable_coverage_slack_notification' => true]
        );
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageNotificationUtility::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(CoverageNotificationUtility::class, $this->utility);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageNotificationUtility::sendCoverageToSlack()
     *
     * @return void
     */
    public function testSendCoverageToSlackWithoutActiveNotification()
    {
        $coverage = \Mockery::mock(Coverage::class);

        $this->registry->shouldReceive('getManager')->once()->andReturn($this->manager);

        $this->utility = new CoverageNotificationUtility(
            $this->registry,
            $this->client,
            $this->router,
            'host',
            'url',
            ['enable_coverage_slack_notification' => false]
        );

        $this->utility->sendCoverageToSlack($coverage, true);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageNotificationUtility::sendCoverageToSlack()
     *
     * @return void
     */
    public function testSendCoverageToSlackWithoutReport()
    {
        $coverage = \Mockery::mock(Coverage::class);
        $branch = \Mockery::mock(Branch::class);
        $project = \Mockery::mock(Project::class);
        $coverageRepository = \Mockery::mock(EntityRepository::class);

        $this->manager->shouldReceive('getRepository')->once()->with('ChapleanGitlabBundle:Coverage')->andReturn($coverageRepository);
        $coverageRepository->shouldReceive('findLastBeforeCoverage')->once()->with($coverage)->andReturnNull();

        $coverage->shouldReceive('getBranch')->once()->andReturn($branch);
        $branch->shouldReceive('getProject')->once()->andReturn($project);
        $project->shouldReceive('getName')->once()->andReturn('project name');
        $branch->shouldReceive('getName')->twice()->andReturn('branch name');
        $coverage->shouldReceive('getPercentCovered')->once()->andReturn(0.125);

        $this->client->shouldReceive('request')->once()->andReturnNull();

        $this->utility->sendCoverageToSlack($coverage, false);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageNotificationUtility::sendCoverageToSlack()
     *
     * @return void
     */
    public function testSendCoverageToSlack()
    {
        $coverage = \Mockery::mock(Coverage::class);
        $branch = \Mockery::mock(Branch::class);
        $project = \Mockery::mock(Project::class);
        $coverageRepository = \Mockery::mock(EntityRepository::class);

        $this->manager->shouldReceive('getRepository')->once()->with('ChapleanGitlabBundle:Coverage')->andReturn($coverageRepository);
        $coverageRepository->shouldReceive('findLastBeforeCoverage')->once()->with($coverage)->andReturnNull();

        $coverage->shouldReceive('getBranch')->once()->andReturn($branch);
        $coverage->shouldReceive('getSha')->once()->andReturn('5dss2f5');
        $branch->shouldReceive('getProject')->once()->andReturn($project);
        $project->shouldReceive('getName')->once()->andReturn('project name');
        $branch->shouldReceive('getName')->twice()->andReturn('branch name');
        $coverage->shouldReceive('getPercentCovered')->once()->andReturn(0.125);

        $this->client->shouldReceive('request')->once()->andReturnNull();
        $this->router->shouldReceive('generate')->once()->withArgs(['app_gitlab_coverage_report', ['sha' => '5dss2f5', 'file' => '']]);

        $this->utility->sendCoverageToSlack($coverage, true);
    }
}
