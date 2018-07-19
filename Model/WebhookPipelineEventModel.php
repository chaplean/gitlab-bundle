<?php

namespace Chaplean\Bundle\GitlabBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class WebhookPipelineEventModel.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Model
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class WebhookPipelineEventModel
{
    /**
     * @var string
     */
    private $objectKind;

    /**
     * @var PipelineModel
     */
    private $objectAttributes;

    /**
     * @var ArrayCollection|BuildModel[]
     */
    private $builds;

    /**
     * @var ProjectModel
     */
    private $project;

    /**
     * WebhookPipelineEventModel constructor.
     */
    public function __construct()
    {
        $this->builds = new ArrayCollection();
    }

    /**
     * Get objectKind.
     *
     * @return string
     */
    public function getObjectKind()
    {
        return $this->objectKind;
    }

    /**
     * Set objectKind.
     *
     * @param string $objectKind
     *
     * @return WebhookPipelineEventModel
     */
    public function setObjectKind($objectKind)
    {
        $this->objectKind = $objectKind;

        return $this;
    }

    /**
     * Get objectAttributes.
     *
     * @return PipelineModel
     */
    public function getObjectAttributes()
    {
        return $this->objectAttributes;
    }

    /**
     * Set objectAttributes.
     *
     * @param PipelineModel $objectAttributes
     *
     * @return WebhookPipelineEventModel
     */
    public function setObjectAttributes(PipelineModel $objectAttributes)
    {
        $this->objectAttributes = $objectAttributes;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPipelineId()
    {
        return $this->getObjectAttributes()->getId();
    }

    /**
     * @return boolean
     */
    public function hasPipelineSucceeded()
    {
        return $this->getObjectAttributes()->isSuccess();
    }

    /**
     * Get project.
     *
     * @return ProjectModel
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set project.
     *
     * @param ProjectModel $project
     *
     * @return WebhookPipelineEventModel
     */
    public function setProject(ProjectModel $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get builds.
     *
     * @return ArrayCollection|BuildModel[]
     */
    public function getBuilds()
    {
        return $this->builds;
    }

    /**
     * Add build.
     *
     * @param BuildModel $build
     *
     * @return WebhookPipelineEventModel
     */
    public function addBuild(BuildModel $build)
    {
        $this->builds->add($build);

        return $this;
    }

    /**
     * Remove build.
     *
     * @param BuildModel $build
     *
     * @return WebhookPipelineEventModel
     */
    public function removeBuild(BuildModel $build)
    {
        $this->builds->removeElement($build);

        return $this;
    }

    /**
     * @return integer|null
     */
    public function getLastSucceededTestBuildId()
    {
        $succeededBuildIds = $this->builds->filter(
            function (BuildModel $build) {
                return $build->isSuccess() && $build->getStage() === 'test';
            }
        )->map(
            function (BuildModel $build) {
                return $build->getId();
            }
        )->toArray();

        if (empty($succeededBuildIds)) {
            return null;
        }

        return max($succeededBuildIds);
    }
}
