<?php

namespace Chaplean\Bundle\GitlabBundle\Form\Handler;

use Chaplean\Bundle\FormHandlerBundle\Form\FailureHandlerInterface;
use Symfony\Component\Form\FormErrorIterator;

/**
 * Class ExceptionFailureHandler.
 *
 * @package   Chaplean\Bundle\GitlabBundle\Form\Handler
 * @author    Matthias - Chaplean <matthias@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class ExceptionFailureHandler implements FailureHandlerInterface
{
    /**
     * @param FormErrorIterator $formErrors
     * @param array             $customErrors
     * @param array             $parameters
     *
     * @return void
     * @throws \RuntimeException
     */
    public function onFailure(FormErrorIterator $formErrors, array $customErrors, array $parameters)
    {
        throw new \RuntimeException();
    }
}
