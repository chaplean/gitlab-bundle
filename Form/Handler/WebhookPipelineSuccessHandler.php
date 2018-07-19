<?php

namespace Chaplean\Bundle\GitlabBundle\Form\Handler;

use Chaplean\Bundle\GitlabBundle\Event\CoverageSendEvent;
use Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel;
use Chaplean\Bundle\GitlabBundle\Utility\BranchUtility;
use Chaplean\Bundle\GitlabBundle\Utility\CoverageReportUtility;
use Chaplean\Bundle\GitlabBundle\Utility\CoverageUtility;
use Chaplean\Bundle\GitlabBundle\Utility\PipelineUtility;
use Chaplean\Bundle\GitlabBundle\Utility\ProjectUtility;
use Chaplean\Bundle\FormHandlerBundle\Form\SuccessHandlerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class WebhookPipelineSuccessHandler.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Form\Handler
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class WebhookPipelineSuccessHandler implements SuccessHandlerInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PipelineUtility
     */
    protected $pipelineUtility;

    /**
     * @var ProjectUtility
     */
    protected $projectUtility;

    /**
     * @var BranchUtility
     */
    protected $branchUtility;

    /**
     * @var CoverageUtility
     */
    protected $coverageUtility;

    /**
     * @var CoverageReportUtility
     */
    protected $coverageReportUtility;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * WebhookPipelineSuccessHandler constructor.
     *
     * @param RegistryInterface        $registry
     * @param PipelineUtility          $pipelineUtility
     * @param ProjectUtility           $projectUtility
     * @param BranchUtility            $branchUtility
     * @param CoverageUtility          $coverageUtility
     * @param CoverageReportUtility    $coverageReportUtility
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        RegistryInterface $registry,
        PipelineUtility $pipelineUtility,
        ProjectUtility $projectUtility,
        BranchUtility $branchUtility,
        CoverageUtility $coverageUtility,
        CoverageReportUtility $coverageReportUtility,
        EventDispatcherInterface $dispatcher
    ) {
        $this->em = $registry->getManager();
        $this->pipelineUtility = $pipelineUtility;
        $this->projectUtility = $projectUtility;
        $this->branchUtility = $branchUtility;
        $this->coverageUtility = $coverageUtility;
        $this->coverageReportUtility = $coverageReportUtility;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param mixed|WebhookPipelineEventModel $data
     * @param array                           $parameters
     *
     * @return array|mixed
     */
    public function onSuccess($data, array $parameters)
    {
        if (!$data->hasPipelineSucceeded()) {
            return ['answer' => 'ignored'];
        }

        $dataPipeline = $data->getObjectAttributes();
        $projectModel = $data->getProject();

        $project = $this->projectUtility->getFromProjectModel($projectModel);
        $branch = $this->branchUtility->getFromProjectAndName($project, $dataPipeline->getBranch());

        $percentCoverage = (float) $dataPipeline->getCoverage() / 100.00;

        // Retry with a query to the api
        if ($percentCoverage <= 0) {
            try {
                $dataPipeline = $this->pipelineUtility->getPipelineModel($projectModel->getGitlabId(), $data->getPipelineId());
                $percentCoverage = (float) $dataPipeline->getCoverage() / 100.00;
            } catch (\Exception $e) {
                return ['answer' => 'failed'];
            }
        }

        $sha = $dataPipeline->getSha();

        // Retry with an existing coverage based on the same sha.
        if ($percentCoverage <= 0) {
            $percentCoverage = $this->coverageUtility->getCoveragePercentWithSameSha($project, $sha);
        }

        // Still can't get the coverage, bail out.
        if ($percentCoverage <= 0) {
            return ['answer' => 'failed'];
        }

        $coverage = $this->coverageUtility->create($branch, $sha, $percentCoverage);

        $sendReport = $this->coverageReportUtility->saveCoverageReport($sha, $project->getGitlabId(), $data->getLastSucceededTestBuildId());

        $this->dispatcher->dispatch(CoverageSendEvent::NAME, new CoverageSendEvent($coverage, $sendReport));
        return ['answer' => 'acknowledged'];
    }
}
