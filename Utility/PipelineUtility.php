<?php

namespace Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabClientBundle\Api\GitlabApi;
use Chaplean\Bundle\GitlabBundle\Form\Handler\ExceptionFailureHandler;
use Chaplean\Bundle\GitlabBundle\Form\Handler\ForwardDataSuccessHandler;
use Chaplean\Bundle\GitlabBundle\Form\Type\PipelineType;
use Chaplean\Bundle\GitlabBundle\Model\PipelineModel;
use Chaplean\Bundle\FormHandlerBundle\Form\FormHandler;

/**
 * Class PipelineUtility.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Utility
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class PipelineUtility
{
    /**
     * @var GitlabApi
     */
    protected $gitlabApi;

    /**
     * @var FormHandler
     */
    protected $formHandler;

    /**
     * PipelineUtility constructor.
     *
     * @param GitlabApi   $gitlabApi
     * @param FormHandler $formHandler
     */
    public function __construct(GitlabApi $gitlabApi, FormHandler $formHandler)
    {
        $this->gitlabApi = $gitlabApi;
        $this->formHandler = $formHandler;
    }

    /**
     * @param integer $projectGitlabId
     * @param integer $pipelineId
     *
     * @return PipelineModel
     *
     * @throws \RuntimeException
     */
    public function getPipelineModel($projectGitlabId, $pipelineId)
    {
        $response = $this->gitlabApi->getPipeline()
            ->bindUrlParameters(
                [
                    'project_id'  => $projectGitlabId,
                    'pipeline_id' => $pipelineId
                ]
            )
            ->exec();

        if (!$response->succeeded()) {
            throw new \RuntimeException();
        }

        $dataPipeline = $this->formHandler->successHandler(new ForwardDataSuccessHandler())
            ->failureHandler(new ExceptionFailureHandler())
            ->handle(PipelineType::class, new PipelineModel(), $response->getContent());

        return $dataPipeline;
    }
}
