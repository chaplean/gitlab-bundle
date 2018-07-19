<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Chaplean\Bundle\GitlabBundle\Utility\CoverageUtility;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CoverageUtilityTest.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageUtilityTest extends MockeryTestCase
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
    private $coverageRepository;

    /**
     * @var CoverageUtility
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
        $this->coverageRepository = \Mockery::mock(EntityRepository::class);

        $this->registry->shouldReceive('getManager')->once()->andReturn($this->manager);
        $this->manager->shouldReceive('getRepository')->once()->with(Coverage::class)->andReturn($this->coverageRepository);

        $this->utility = new CoverageUtility($this->registry);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageUtility::__construct()
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->assertInstanceOf(CoverageUtility::class, $this->utility);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageUtility::create()
     *
     * @return void
     */
    public function testCreate()
    {
        $branch = \Mockery::mock(Branch::class);

        $this->manager->shouldReceive('persist')->once()->andReturnNull();

        $result = $this->utility->create($branch, 'dssdq892dsq', 0.25);

        $this->assertEquals($branch, $result->getBranch());
        $this->assertEquals('dssdq892dsq', $result->getSha());
        $this->assertEquals(0.25, $result->getPercentCovered());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageUtility::getCoveragePercentWithSameSha()
     *
     * @return void
     */
    public function testGetCoveragePercentWithSameShaWithoutResults()
    {
        $project = \Mockery::mock(Project::class);

        $this->coverageRepository->shouldReceive('findOneByShaAndProject')->once()->withArgs(['dssdq892dsq', $project])->andReturnNull();

        $result = $this->utility->getCoveragePercentWithSameSha($project, 'dssdq892dsq');

        $this->assertNull($result);
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Utility\CoverageUtility::getCoveragePercentWithSameSha()
     *
     * @return void
     */
    public function testGetCoveragePercentWithSameSha()
    {
        $project = \Mockery::mock(Project::class);
        $coverage = \Mockery::mock(Coverage::class);

        $this->coverageRepository->shouldReceive('findOneByShaAndProject')->once()->withArgs(['dssdq892dsq', $project])->andReturn($coverage);

        $coverage->shouldReceive('getPercentCovered')->once()->andReturn(0.256);

        $result = $this->utility->getCoveragePercentWithSameSha($project, 'dssdq892dsq');

        $this->assertEquals(0.256, $result);
    }
}
