<?php

namespace JamesClark32\Websocket\Tests\StreamWrapper;

use JamesClark32\Websocket\StreamWrapper;
use PHPUnit\Framework\TestCase;

class SetPortTest extends TestCase
{
    /** @test */
    public function port_is_null_by_default()
    {
        $streamWrapper = new StreamWrapper();
        $this->assertNull($streamWrapper->getPort());
    }

    /** @test */
    public function getPort_method_returns_value_provided_to_setPort_method()
    {
        $streamWrapper = new StreamWrapper();

        for ($i = 0; $i <= 3; $i++) {
            $randomPortNumber = rand(1, 9999);
            $streamWrapper->setPort($randomPortNumber);
            $this->assertEquals($randomPortNumber, $streamWrapper->getPort());
        }
    }

    /** @test */
    public function default_post_is_set_when_no_port_specified()
    {
        $streamWrapper = new StreamWrapper();
        $streamWrapper->initialize();
        $this->assertEquals(8830, $streamWrapper->getPort());
    }
}
