<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Model;

use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Chaplean\Bundle\GitlabBundle\Model\BuildModel;
use Chaplean\Bundle\GitlabBundle\Model\ProjectModel;
use PHPUnit\Framework\TestCase;

/**
 * Class ProjectModelTest.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Model
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class ProjectModelTest extends TestCase
{
    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\ProjectModel::getGitlabId()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\ProjectModel::setGitlabId()
     *
     * @return void
     */
    public function testGitlabId()
    {
        $model = new ProjectModel();

        $this->assertNull($model->getGitlabId());

        $model->setGitlabId(18);
        $this->assertEquals(18, $model->getGitlabId());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\ProjectModel::getName()
     * @covers \Chaplean\Bundle\GitlabBundle\Model\ProjectModel::setName()
     *
     * @return void
     */
    public function testName()
    {
        $model = new ProjectModel();

        $this->assertNull($model->getName());

        $model->setName('name');
        $this->assertEquals('name', $model->getName());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\ProjectModel::getRelatedEntityClass()
     *
     * @return void
     */
    public function testGetRelatedEntityClass()
    {
        $model = new ProjectModel();

        $this->assertEquals(Project::class, $model->getRelatedEntityClass());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Model\ProjectModel::getKeyElements()
     *
     * @return void
     */
    public function testGetKeyElements()
    {
        $model = new ProjectModel();

        $this->assertEquals(['gitlabId' => null], $model->getKeyElements());

        $model->setGitlabId(15);
        $this->assertEquals(['gitlabId' => 15], $model->getKeyElements());
    }
}
