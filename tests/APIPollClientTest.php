<?php

namespace TelegramBot\Test;

use TelegramBot\APIMessage;
use TelegramBot\APIPollClient;
use React\HttpClient\Client;

use Prophecy\Argument;

class APIPollTest extends TestBase {

  /** @var \TelegramBot\Bot */
  private $object;
  private $clientProphecy;

  public function setUp() {
    $this->clientProphecy = $this->prophesize(Client::class);
    $this->object = new APIPollClient(
      'SOME BOT TOKEN',
      $this->clientProphecy->reveal()
    );

  }

  public function testObject() {
    $this->assertInstanceOf(APIPollClient::class, $this->object);
  }

  /**
   *
   */
  public function testPoll()
  {
    $request = $this->prophesize( \React\HttpClient\Request::class);
    $request->on('response', Argument::cetera())->shouldBeCalled();
    #$request->on('error', Argument::cetera())->shouldBeCalled();
    $request->end()->shouldBeCalled();

    $this->clientProphecy
      ->request('GET', Argument::cetera())
      ->willReturn($request->reveal())
      ->shouldBeCalled();

    $executed = false;
    $this->object->poll(function() use (&$executed) { $executed = true; });
    $this->assertTrue($executed);
  }

  public function testGetResponseHeaders() {
    $this->assertEquals(
      ['Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => 11],
      $this->object->getResponseHeaders('test_string')
    );
  }

  public function testSend() {

    $request = $this->prophesize( \React\HttpClient\Request::class);
    $request->end(Argument::any())->shouldBeCalled();

    $this->clientProphecy
      ->request('POST', Argument::cetera())
      ->willReturn($request->reveal())
      ->shouldBeCalled();

    $this->object->send('pong', new APIMessage([
      'message' => [
        'message_id' => 123,
        'chat' => ['id' => 1]
        ]
      ])
    );
  }

  public function testMArkMessageAnswered()
  {
    $apiMessageProphecy = $this->prophesize(APIMessage::class);
    $apiMessageProphecy->getUpdateId()->shouldBeCalled();
    $this->object->markMessageHandled($apiMessageProphecy->reveal());
  }
}
