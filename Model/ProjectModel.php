<?php

namespace Chaplean\Bundle\GitlabBundle\Model;

use Chaplean\Bundle\GitlabBundle\Entity\Project;

/**
 * Class ProjectModel.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Model
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class ProjectModel
{
    /**
     * @var integer
     */
    private $gitlabId;

    /**
     * @var string
     */
    private $name;

    /**
     * Get gitlabId.
     *
     * @return integer
     */
    public function getGitlabId()
    {
        return $this->gitlabId;
    }

    /**
     * Set gitlabId.
     *
     * @param integer $gitlabId
     *
     * @return ProjectModel
     */
    public function setGitlabId($gitlabId)
    {
        $this->gitlabId = $gitlabId;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return ProjectModel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getRelatedEntityClass()
    {
        return Project::class;
    }

    /**
     * @return array
     */
    public function getKeyElements()
    {
        return ['gitlabId' => $this->gitlabId];
    }
}
