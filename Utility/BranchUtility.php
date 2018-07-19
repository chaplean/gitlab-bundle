<?php

namespace Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class BranchUtility.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Utility
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class BranchUtility
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var EntityRepository
     */
    private $branchRepository;

    /**
     * BranchUtility constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->branchRepository = $this->em->getRepository(Branch::class);
    }

    /**
     * @param Project $project
     * @param string  $name
     *
     * @return Branch
     */
    public function getFromProjectAndName(Project $project, $name)
    {
        $branch = $this->branchRepository->findOneBy(['name' => $name, 'project' => $project->getId()]);

        if ($branch === null) {
            $branch = new Branch();
            $branch->setProject($project);
            $branch->setName($name);
            $project->addBranch($branch);

            $this->em->persist($branch);
        }

        return $branch;
    }
}
