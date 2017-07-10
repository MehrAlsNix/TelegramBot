<?php

namespace TelegramBot\Test;

use TelegramBot\BotFactory;
use TelegramBot\BotInterface;

use Prophecy\Argument;

class BotFactoryTest extends TestBase
{
    private $object;
    private $loopProphecy;

    function setUp() {
        $this->loopProphecy = $this->prophesize(\React\EventLoop\LoopInterface::class);
        $this->object = new BotFactory($this->loopProphecy->reveal());
    }

    function testObject() {
      $this->assertInstanceOf(BotFactory::class, $this->object);
    }

    function testCreateWithToken() {
      $this->assertInstanceOf(BotInterface::class, $this->object->createWithToken('token'));
    }
}
