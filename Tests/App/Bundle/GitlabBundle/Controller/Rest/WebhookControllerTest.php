<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Controller\Rest;

use Chaplean\Bundle\GitlabBundle\Entity\Branch;
use Chaplean\Bundle\GitlabBundle\Entity\Project;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebhookControllerTest.
 *
 * @package   Tests\Chaplean\Bundle\GitlabBundle\Controller\Rest
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class WebhookControllerTest extends TestCase
{
    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Controller\Rest\WebhookController::postAction()
     *
     * @return void
     */
    public function testPostWebhookBadToken()
    {
        $this->markTestSkipped('Skipped until guzzle is mocked');
        $requestPayload = json_decode(file_get_contents(__DIR__ . '/../../../../../Resources/gitlabWebhookPipelineEvent.json'), true);
        $client = self::createClient();
        $client->request(
            'POST',
            '/gitlab/webhook',
            $requestPayload,
            [],
            [
                'HTTP_X-Gitlab-Event' => 'Pipeline Hook',
                'HTTP_X-Gitlab-Token' => 'badtoken',
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Controller\Rest\WebhookController::postAction()
     *
     * @return void
     */
    public function testPostWebhookUnhandledEvent()
    {
        $this->markTestSkipped('Skipped until guzzle is mocked');
        $requestPayload = json_decode(file_get_contents(__DIR__ . '/../../../../../Resources/gitlabWebhookPushEvent.json'), true);
        $client = self::createClient();
        $client->request(
            'POST',
            '/gitlab/webhook',
            $requestPayload,
            [],
            [
                'HTTP_X-Gitlab-Event' => 'Push Hook',
                'HTTP_X-Gitlab-Token' => 'testtoken',
            ]
        );

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * @group 25707
     *
     * @covers \Chaplean\Bundle\GitlabBundle\Controller\Rest\WebhookController::postAction()
     *
     * @return void
     */
    public function testPostWebhookPipelineEventSavesNewProjectsAndBranches()
    {
        $this->markTestSkipped('Skipped until guzzle is mocked');

        $requestPayload = json_decode(file_get_contents(__DIR__ . '/../../../../../Resources/gitlabWebhookPipelineEvent.json'), true);
        $client = self::createClient();

        $projectRepo = $this->em->getRepository(Project::class);
        $branchRepo = $this->em->getRepository(Branch::class);

        $this->assertCount(0, $projectRepo->findAll());
        $this->assertCount(0, $branchRepo->findAll());

        $client->request(
            'POST',
            '/gitlab/webhook',
            $requestPayload,
            [],
            [
                'HTTP_X-Gitlab-Event' => 'Pipeline Hook',
                'HTTP_X-Gitlab-Token' => 'testtoken',
            ]
        );
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('{"answer":"acknowledged"}', $response->getContent());

        $this->assertCount(1, $projectRepo->findAll());
        $this->assertCount(1, $branchRepo->findAll());

        $client = self::createClient();
        $client->request(
            'POST',
            '/gitlab/webhook',
            $requestPayload,
            [],
            [
                'HTTP_X-Gitlab-Event' => 'Pipeline Hook',
                'HTTP_X-Gitlab-Token' => 'testtoken',
            ]
        );
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('{"answer":"acknowledged"}', $response->getContent());

        $this->assertCount(1, $projectRepo->findAll());
        $this->assertCount(1, $branchRepo->findAll());
    }
}
