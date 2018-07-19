<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Model;

use Chaplean\Bundle\GitlabBundle\Model\PipelineModel;
use PHPUnit\Framework\TestCase;

/**
 * Class PipelineModelTest.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Model
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class PipelineModelTest extends TestCase
{
    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::getId()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::setId()
     *
     * @return void
     */
    public function testId()
    {
        $model = new PipelineModel();

        $this->assertNull($model->getId());

        $model->setId(18);
        $this->assertEquals(18, $model->getId());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::getStatus()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::setStatus()
     *
     * @return void
     */
    public function testStatus()
    {
        $model = new PipelineModel();

        $this->assertNull($model->getStatus());

        $model->setStatus('status');
        $this->assertEquals('status', $model->getStatus());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::getBranch()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::setBranch()
     *
     * @return void
     */
    public function testBranch()
    {
        $model = new PipelineModel();

        $this->assertNull($model->getBranch());

        $model->setBranch('branch');
        $this->assertEquals('branch', $model->getBranch());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::getSha()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::setSha()
     *
     * @return void
     */
    public function testSha()
    {
        $model = new PipelineModel();

        $this->assertNull($model->getSha());

        $model->setSha('sha');
        $this->assertEquals('sha', $model->getSha());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::getCoverage()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::setCoverage()
     *
     * @return void
     */
    public function testCoverage()
    {
        $model = new PipelineModel();

        $this->assertNull($model->getCoverage());

        $model->setCoverage(35.0);
        $this->assertEquals(35.0, $model->getCoverage());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::isSuccess()
     *
     * @return void
     */
    public function testIsSuccessTrue()
    {
        $model = new PipelineModel();
        $model->setStatus('success');

        $this->assertTrue($model->isSuccess());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\PipelineModel::isSuccess()
     *
     * @return void
     */
    public function testIsSuccessFalse()
    {
        $model = new PipelineModel();
        $this->assertFalse($model->isSuccess());

        $model->setStatus('anything');
        $this->assertFalse($model->isSuccess());
    }
}
