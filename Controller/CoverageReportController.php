<?php

namespace Chaplean\Bundle\GitlabBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CoverageController.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Controller
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageReportController extends Controller
{
    /**
     * @param string $sha
     * @param string $file
     *
     * @return Response
     */
    public function indexAction($sha, $file)
    {
        $gitlabBundleConfig = $this->getParameter('chaplean_gitlab.config');
        $kernelRootDir = $this->getParameter('kernel.root_dir');
        $uploadsDir = $this->getParameter('coverages_dir');
        $coverageReportDir = $gitlabBundleConfig['coverage_report_dir'];

        $pathUpload = $kernelRootDir . $uploadsDir;

        if ($file === '') {
            $file = 'index.html';
        }

        return new Response(file_get_contents($pathUpload . $sha  . $coverageReportDir . '/' . $file));
    }
}
