<?php

namespace Chaplean\Bundle\GitlabBundle\Controller\Rest;

use Chaplean\Bundle\GitlabBundle\Form\Type\WebhookPipelineEventType;
use Chaplean\Bundle\GitlabBundle\Model\WebhookPipelineEventModel;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebhookController.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Controller\Rest
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 *
 * @Annotations\Prefix("webhook")
 */
class WebhookController extends FOSRestController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function postAction(Request $request)
    {
        return $this->get('chaplean_form_handler.form.controller_form_handler')
            ->successHandler('app_gitlab.form.success_handler.webhook_pipeline')
            ->handle(WebhookPipelineEventType::class, new WebhookPipelineEventModel(), $request);
    }
}
