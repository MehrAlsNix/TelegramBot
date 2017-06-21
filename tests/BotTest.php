<?php

namespace TelegramBot\Test;

use TelegramBot\Bot;
use React\HttpClient\Client;

use Prophecy\Argument;

class BotTest extends TestBase {
  /** @var \TelegramBot\Bot */
  private $object;
  private $clientProphecy;

  public function setUp() {
    $this->clientProphecy = $this->prophesize(Client::class);
    $this->object = new Bot("SOME_BOT_TOKEN");
    $this->object->setClient($this->clientProphecy->reveal());

  }

  public function testObject() {
    $this->assertInstanceOf(Bot::class, $this->object);
  }

  /**
   *
   */
  public function testPoll()
  {
    $this->markTestIncomplete( 'must thing about the http part first!');
    $response = $this->prophesize( \React\HttpClient\Response::class);

    $request = $this->prophesize( \React\HttpClient\Request::class);
    $request->on('response', Argument::any())
      ->willReturn($response);

    $this->clientProphecy
      ->request(Argument::any())
      ->willReturn($request->reveal())
      ->shouldBeCalled();

    $this->object->poll();
  }


  public function testGetResonder() {
    $returnType = gettype($this->object->getResponder([]));
    $this->assertEquals('object', $returnType);
  }

  public function testGetResponseHeaders() {

    $this->assertEquals(
      ['Content-Type' => 'application/x-www-form-urlencoded', 'Content-Length' => 11],
      $this->object->getResponseHeaders('test_string')
    );
  }

  public function testGetMessagesRequest() {
    $request = $this->prophesize( \React\HttpClient\Request::class);

    $this->clientProphecy
      ->request('GET', Argument::cetera())
      ->willReturn($request->reveal())
      ->shouldBeCalled();
    $this->object->getMassagesRequest();
  }

  public function testSendResponse() {

    $request = $this->prophesize( \React\HttpClient\Request::class);
    $request->end(Argument::any())->shouldBeCalled();

    $this->clientProphecy
      ->request('POST', Argument::cetera())
      ->willReturn($request->reveal())
      ->shouldBeCalled();

    $this->object->sendResponse('pong', [
      'message' => [
        'message_id' => 123,
        'chat' => ['id' => 1]
        ]
      ]
    );
  }
}
