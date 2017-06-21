<?php

namespace TelegramBot\Test;

use TelegramBot\Bot;
use React\HttpClient\Client;

use Prophecy\Argument;

class BotTest extends TestBase {

  private $clientProphecy;

  public function setUp() {
    $this->clientProphecy = $this->prophesize(Client::class);

  }

  public function testObject() {
    $object = new Bot("SOME_BOT_TOKEN");
    $object->setClient($this->clientProphecy->reveal());
    $this->assertInstanceOf(Bot::class, $object);

    return $object;
  }

  /**
   * @depends testObject
   */
  public function testPoll($object)
  {
    $this->markTestIncomplete( 'msut thing about the http part first!');
    $response = $this->prophesize( \React\HttpClient\Response::class);

    $request = $this->prophesize( \React\HttpClient\Request::class);
    $request->on('response', Argument::any())
      ->willReturn($response);

    $this->clientProphecy
      ->request(Argument::any())
      ->willReturn($request->reveal())
      ->shouldBeCalled();

    $object->poll();
  }

}
