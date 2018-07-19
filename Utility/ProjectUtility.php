<?php

namespace Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Chaplean\Bundle\GitlabBundle\Model\ProjectModel;
use Chaplean\Bundle\GitlabBundle\Repository\ProjectRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ProjectUtility.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Utility
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class ProjectUtility
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * ProjectUtility constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->projectRepository = $this->em->getRepository(Project::class);
    }

    /**
     * @param ProjectModel $projectModel
     *
     * @return Project
     */
    public function getFromProjectModel(ProjectModel $projectModel)
    {
        $projectGitlabId = $projectModel->getGitlabId();
        $project = $this->projectRepository->findOneByGitlabId($projectGitlabId);

        if ($project === null) {
            $project = new Project();
            $project->setGitlabId($projectGitlabId);
            $project->setName($projectModel->getName());

            $this->em->persist($project);
        }

        return $project;
    }
}
