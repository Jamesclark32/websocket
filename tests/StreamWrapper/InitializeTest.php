<?php

namespace JamesClark32\Websocket\Tests\StreamWrapper;

use JamesClark32\Websocket\StreamWrapper;
use PHPUnit\Framework\TestCase;

class InitializeTest extends TestCase
{
    /** @test */
    public function initialize_method_creates_resources()
    {
        $streamWrapper = new StreamWrapper();
        $this->assertNull($streamWrapper->getStreamSocket());

        $streamWrapper->initialize();

        $this->assertTrue(is_resource($streamWrapper->getStreamSocket()));
    }
}
