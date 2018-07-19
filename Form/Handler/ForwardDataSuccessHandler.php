<?php

namespace Chaplean\Bundle\GitlabBundle\Form\Handler;

use Chaplean\Bundle\FormHandlerBundle\Form\SuccessHandlerInterface;

/**
 * Class ForwardDataSuccessHandler.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Form\Handler
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class ForwardDataSuccessHandler implements SuccessHandlerInterface
{
    /**
     * @param mixed $data
     * @param array $parameters
     *
     * @return mixed
     */
    public function onSuccess($data, array $parameters)
    {
        return $data;
    }
}
