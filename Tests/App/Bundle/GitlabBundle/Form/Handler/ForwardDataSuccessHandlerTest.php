<?php

namespace Tests\Chaplean\Bundle\GitlabBundle\Form\Handler;

use Chaplean\Bundle\GitlabBundle\Form\Handler\ForwardDataSuccessHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class ForwardDataSuccessHandlerTest.
 *
 * @author    Hugo - Chaplean <hugo@chaplean.coop>
 * @copyright 2014 - 2018 Chaplean (https://www.chaplean.coop)
 */
class ForwardDataSuccessHandlerTest extends TestCase
{
    /**
     * @covers \Chaplean\Bundle\GitlabBundle\Form\Handler\ForwardDataSuccessHandler::onSuccess()
     *
     * @return void
     */
    public function testOnSuccess()
    {
        $handler = new ForwardDataSuccessHandler();
        $data = ['a' => 'b'];

        $result = $handler->onSuccess($data, []);

        $this->assertEquals($result, $data);
    }
}
