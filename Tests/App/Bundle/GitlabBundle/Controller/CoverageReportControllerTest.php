<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CoverageReportControllerTest.
 *
 * @package   Tests\Chaplean\Bundle\GitlabBundle\Controller
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class CoverageReportControllerTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Controller\CoverageReportController::indexAction()
     *
     * @return void
     */
    public function testIndexActionNotLogged()
    {
        $this->markTestSkipped('Skipped until guzzle is mocked');
        $client = self::createClient();

        $client->request(
            'GET',
            '/coverage/123456/'
        );

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Controller\CoverageReportController::indexAction()
     *
     * @return void
     */
    public function testIndexAction()
    {
        $this->markTestSkipped('Skipped until guzzle is mocked');
        $client = self::createOAuthClientWith('user-tom');

        $client->request(
            'GET',
            '/coverage/123456/'
        );

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
