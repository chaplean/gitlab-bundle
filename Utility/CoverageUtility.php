<?php

namespace Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Chaplean\Bundle\GitlabBundle\Repository\CoverageRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CoverageUtility.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Utility
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageUtility
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var CoverageRepository
     */
    private $coverageRepository;

    /**
     * CoverageUtility constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->coverageRepository = $this->em->getRepository(Coverage::class);
    }

    /**
     * @param Project $project
     * @param string  $sha
     *
     * @return float|null
     */
    public function getCoveragePercentWithSameSha(Project $project, $sha)
    {
        $coverage = $this->coverageRepository->findOneByShaAndProject($sha, $project);

        if ($coverage === null) {
            return null;
        }

        return $coverage->getPercentCovered();
    }

    /**
     * @param Branch $branch
     * @param string $sha
     * @param float  $percentCoverage
     *
     * @return Coverage
     */
    public function create(Branch $branch, $sha, $percentCoverage)
    {
        $coverage = new Coverage();
        $coverage->setBranch($branch);
        $coverage->setPercentCovered($percentCoverage);
        $coverage->setSha($sha);

        $this->em->persist($coverage);

        return $coverage;
    }
}
