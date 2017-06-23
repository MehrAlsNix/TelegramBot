<?php

namespace TelegramBot\Test;

use League\Event\ListenerInterface;
use League\Event\EventInterface;
use TelegramBot\Command\PingCommand;
use TelegramBot\APIMessage;


class PingCommandTest extends TestBase {

  private $object;

  public function setUp() {
    $this->object = new PingCommand();
  }

  /**
   * @test
   */
  public function isListener() {
      $this->assertTrue($this->object->isListener($this->object));
  }

  /**
   * @test
   */
  public function handle() {
      $event = $this->prophesize(EventInterface::class);
      $callBack = $this->prophesize( \CallableInterface::class );
      $event->stopPropagation()->shouldBeCalled();
      $called = false;
      $param = [
        'responder' => function() use (&$called){
          $called = true;
        },
        'message' => new APIMessage([])
      ];

      $this->object->handle($event->reveal(), $param);
      $this->assertTrue($called);
  }
}
