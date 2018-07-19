<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Form\Handler;

use Chaplean\Bundle\GitlabBundle\Form\Handler\ExceptionFailureHandler;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Symfony\Component\Form\FormErrorIterator;

/**
 * Class ExceptionFailureHandlerTest.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class ExceptionFailureHandlerTest extends MockeryTestCase
{
    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Form\Handler\ExceptionFailureHandler::onFailure()
     *
     * @expectedException \RuntimeException
     *
     * @return void
     */
    public function testOnFailure()
    {
        $formErrors = \Mockery::mock(FormErrorIterator::class);

        $handler = new ExceptionFailureHandler();

        $handler->onFailure($formErrors, [], []);
    }
}
