<?php

namespace TelegramBot\Test;

use React\HttpClient\Response;
use TelegramBot\Bot;
use TelegramBot\APIMessage;
use TelegramBot\APIPollClient;

use Prophecy\Argument;

class BotTest extends TestBase {
  /** @var \TelegramBot\Bot */
  private $object;
  private $clientProphecy;

  public function setUp() {
    $this->clientProphecy = $this->prophesize(APIPollClient::class);
    $this->object = new Bot($this->clientProphecy->reveal());

  }

  public function testObject() {
    $this->assertInstanceOf(Bot::class, $this->object);
  }

  /**
   *
   */
  public function testPoll()
  {
    $this->clientProphecy
      ->poll(Argument::any())
      ->shouldBeCalled();

    $this->object->poll();
  }

  public function testHandlePollResponse()
  {
    $response = $this->prophesize( Response::class);
    $response->on('data', Argument::cetera())->shouldBeCalled();
    $response->on('error', Argument::cetera())->shouldBeCalled();

    $this->object->_handlePollResponse($response->reveal());
  }

  public function testHandlePollData()
  {
    $data = '{"result": [{"message": {"text": "test"}}]}';
    $response = $this->prophesize( Response::class);
    $this->clientProphecy
      ->markMessageHandled(Argument::type(APIMessage::class))
      ->shouldBeCalled()
    ;
    $this->object->_handlePollData($data, $response->reveal());
  }
  public function testHandlePollDataMissingText()
  {
    $data = '{"result": [{}]}';
    $response = $this->prophesize( Response::class);
    $this->clientProphecy
      ->markMessageHandled(Argument::type(APIMessage::class))
      ->shouldBeCalled()
    ;
    $this->object->_handlePollData($data, $response->reveal());
  }

  /**
  * @expectedException \Exception
  */
  public function testHandlePollError()
  {
    $response = $this->prophesize( Response::class);
    $this->object->_handlePollError($response->reveal());
  }
}
