<?php

namespace Chaplean\Bundle\GitlabBundle\Utility;

use Chaplean\Bundle\GitlabBundle\Api\GitlabApi;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class CoverageReportUtility.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Utility
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageReportUtility
{
    /**
     * @var GitlabApi
     */
    protected $gitlabApi;

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $coveragePath;

    /**
     * @var string
     */
    protected $coverageReportDir;

    /**
     * @var boolean
     */
    protected $isCoverageReportEnable;

    /**
     * CoverageReportUtility constructor.
     *
     * @param GitlabApi       $gitlabApi
     * @param LoggerInterface $logger
     * @param array           $parameters
     * @param string          $kernelRootDir
     * @param string          $coverageDir
     *
     */
    public function __construct(GitlabApi $gitlabApi, LoggerInterface $logger, array $parameters, $kernelRootDir, $coverageDir)
    {
        $this->gitlabApi = $gitlabApi;
        $this->logger = $logger;
        $this->coveragePath = $kernelRootDir . $coverageDir;

        $this->fileSystem = new Filesystem();
        $this->isCoverageReportEnable = (boolean) $parameters['enable_coverage_report'];
        $this->coverageReportDir = $parameters['coverage_report_dir'];
    }

    /**
     * @param string  $sha
     * @param integer $gitlabProjectId
     * @param integer $gitlabJobId
     *
     * @return boolean
     */
    public function saveCoverageReport($sha, $gitlabProjectId, $gitlabJobId = null)
    {
        $coverageWithShaDir = $this->coveragePath . $sha;
        $temporaryFilePath = $coverageWithShaDir . '.zip';

        if (!$this->isCoverageReportEnable) {
            return false;
        }

        if ($this->isCoverageReportExist($sha)) {
            return true;
        }

        if ($gitlabJobId === null) {
            return false;
        }

        $this->clearFile($coverageWithShaDir);
        $content = $this->getCoverageReportContent($gitlabProjectId, $gitlabJobId);

        if ($content === null) {
            return false;
        }

        try {
            file_put_contents($temporaryFilePath, $content);

            $zip = new \ZipArchive();
            $zip->open($temporaryFilePath);

            $this->fileSystem->mkdir($coverageWithShaDir);
            $zip->extractTo($coverageWithShaDir);
        } catch (\Exception $e) {
            $this->logger->error('CoverageReportUtility [saveCoverageReport]: ' . $e->getMessage());
        } finally {
            $this->clearFile($temporaryFilePath);
        }

        $success = $this->isCoverageReportExist($sha);

        if (!$success) {
            $this->clearFile($coverageWithShaDir);
        }

        return $success;
    }

    /**
     * @param integer $gitlabProjectId
     * @param integer $gitlabJobId
     *
     * @return string|null
     */
    public function getCoverageReportContent($gitlabProjectId, $gitlabJobId)
    {
        $response = $this->gitlabApi->getArtifacts()
            ->bindUrlParameters(
                [
                    'project_id' => $gitlabProjectId,
                    'job_id'     => $gitlabJobId
                ]
            )
            ->exec();

        if (!$response->succeeded()) {
            return null;
        }

        return $response->getContent();
    }

    /**
     * @param string $sha
     *
     * @return boolean
     */
    public function isCoverageReportExist($sha)
    {
        if (!is_dir($this->coveragePath . $sha)) {
            return false;
        }

        return is_dir($this->coveragePath . $sha . $this->coverageReportDir);
    }

    /**
     * @param string $path
     *
     * @return void
     */
    public function clearFile($path)
    {
        try {
            $this->fileSystem->remove($path);
        } catch (\Exception $e) {
            $this->logger->error('CoverageReportUtility [clearFile]: ' . $e->getMessage());
        }
    }
}
