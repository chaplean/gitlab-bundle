<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Model;

use Chaplean\Bundle\GitlabBundle\Model\BuildModel;
use PHPUnit\Framework\TestCase;

/**
 * Class BuildModelTest.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Model
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class BuildModelTest extends TestCase
{
    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\BuildModel::getId()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\BuildModel::setId()
     *
     * @return void
     */
    public function testId()
    {
        $model = new BuildModel();

        $this->assertNull($model->getId());

        $model->setId(18);
        $this->assertEquals(18, $model->getId());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\BuildModel::getStatus()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\BuildModel::setStatus()
     *
     * @return void
     */
    public function testStatus()
    {
        $model = new BuildModel();

        $this->assertNull($model->getStatus());

        $model->setStatus('status');
        $this->assertEquals('status', $model->getStatus());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\BuildModel::getStage()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\BuildModel::setStage()
     *
     * @return void
     */
    public function testStage()
    {
        $model = new BuildModel();

        $this->assertNull($model->getStage());

        $model->setStage('stage');
        $this->assertEquals('stage', $model->getStage());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\BuildModel::isSuccess()
     *
     * @return void
     */
    public function testIsSuccessTrue()
    {
        $model = new BuildModel();
        $model->setStatus('success');

        $this->assertTrue($model->isSuccess());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\BuildModel::isSuccess()
     *
     * @return void
     */
    public function testIsSuccessFalse()
    {
        $model = new BuildModel();
        $this->assertFalse($model->isSuccess());

        $model->setStatus('anything');
        $this->assertFalse($model->isSuccess());
    }
}
