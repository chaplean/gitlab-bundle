<?php

namespace Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabBundle\Entity\Coverage;
use Chaplean\Bundle\GitlabBundle\Model\SlackCoverageNotificationModel;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Router;

/**
 * Class CoverageNotificationUtility.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Utility
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageNotificationUtility
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $applicationHost;

    /**
     * @var string
     */
    private $slackChannelUrl;

    /**
     * @var boolean
     */
    private $isEnableCoverageSlackNotification;

    /**
     * CoverageNotificationUtility constructor.
     *
     * @param RegistryInterface $registry
     * @param Client            $client
     * @param Router            $router
     * @param string            $applicationHost
     * @param string            $slackChannelUrl
     * @param array             $gitlabBundleConfig
     */
    public function __construct(RegistryInterface $registry, Client $client, Router $router, $applicationHost, $slackChannelUrl, array $gitlabBundleConfig)
    {
        $this->em = $registry->getManager();
        $this->client = $client;
        $this->router = $router;
        $this->applicationHost = $applicationHost;
        $this->slackChannelUrl = $slackChannelUrl;
        $this->isEnableCoverageSlackNotification = $gitlabBundleConfig['enable_coverage_slack_notification'];
    }

    /**
     * @param Coverage $coverage
     * @param boolean  $sendReport
     *
     * @return void
     */
    public function sendCoverageToSlack(Coverage $coverage, $sendReport)
    {
        $reportUrl = '';

        if (!$this->isEnableCoverageSlackNotification) {
            return;
        }

        if ($sendReport) {
            $reportUrl = $this->applicationHost . $this->router->generate('app_gitlab_coverage_report', ['sha' => $coverage->getSha(), 'file' => '']);
        }

        $lastCoverage = $this->em->getRepository('ChapleanGitlabBundle:Coverage')->findLastBeforeCoverage($coverage);
        $coverageNotification = new SlackCoverageNotificationModel($coverage, $lastCoverage, $reportUrl);

        $this->client->request(
            'POST',
            $this->slackChannelUrl,
            [
                'json' => $coverageNotification->getNotificationData()
            ]
        );
    }
}
