<?php
namespace TelegramBot\Test;

use TelegramBot\ReactRunner;
use TelegramBot\BotInterface;

use React\HttpClient\Client;
use React\EventLoop\LoopInterface;
use League\Event\ListenerInterface;


class ReactRunnerTest extends TestBase {

    public function setUp() {
      $this->clientProphecy = $this->prophesize(Client::class);
      $this->loopProphery = $this->prophesize(LoopInterface::class);
      $loop = \React\EventLoop\Factory::create();
      $this->object = new ReactRunner(
        $loop,
        $this->clientProphecy->reveal()
      );
    }

    public function testObject()
    {
      $this->assertInstanceOf(ReactRunner::class, $this->object);
    }

    public function testObjectFromFactory()
    {
      $this->assertInstanceOf(ReactRunner::class, ReactRunner::create());
    }

    public function testListenerGetsCalledOnEmit()
    {
      $botProphecy = $this->prophesize(BotInterface::class);
      $botProphecy->setClient(\Prophecy\Argument::any())->shouldBeCalled();
      $botProphecy->poll()->shouldBeCalled();

      $this->object->runBot($botProphecy->reveal(), 1);

    }

    /**
     * @expectedException \Exception
     */
    public function testBotPollThrowsException()
    {
      $botProphecy = $this->prophesize(BotInterface::class);
      $botProphecy->setClient(\Prophecy\Argument::any())->shouldBeCalled();
      $botProphecy->poll()->willThrow(new \Exception());

      $this->object->runBot($botProphecy->reveal(), 1);

    }

}
