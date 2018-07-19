<?php

namespace Chaplean\Bundle\GitlabBundle\Repository;

use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;

/**
 * Class CoverageRepository.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Repository
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageRepository extends EntityRepository
{
    /**
     * @param Coverage $coverage
     *
     * @return Coverage
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findLastBeforeCoverage(Coverage $coverage)
    {
        $date = $coverage->getDateAdd();
        $branch = $coverage->getBranch();

        $qb = $this->_em->createQueryBuilder();

        return $qb->select('coverage')
            ->from('ChapleanGitlabBundle:Coverage', 'coverage')
            ->where('coverage.dateAdd < :date')
            ->andWhere('coverage.branch = :branch')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('branch', $branch->getId())
            ->orderBy('coverage.dateAdd', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string  $sha
     * @param Project $project
     *
     * @return Coverage
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByShaAndProject($sha, Project $project)
    {
        $qb = $this->_em->createQueryBuilder();

        return $qb->select('coverage')
            ->from('ChapleanGitlabBundle:Coverage', 'coverage')
            ->join('coverage.branch', 'branch')
            ->where('coverage.sha = :sha')
            ->andWhere('branch.project = :project')
            ->setParameter('sha', $sha)
            ->setParameter('project', $project->getId())
            ->orderBy('coverage.dateAdd', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
