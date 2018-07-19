<?php

namespace Chaplean\Bundle\GitlabBundle\Repository;

use Chaplean\Bundle\GitlabBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;

/**
 * Class ProjectRepository.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Repository
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 *
 * @method Project|null findOneByGitlabId($projectGitlabId)
 */
class ProjectRepository extends EntityRepository
{
}
