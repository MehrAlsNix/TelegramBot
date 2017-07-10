<?php

namespace TelegramBot\Test;
use TelegramBot\Test\TestBase;
use TelegramBot\APIMessage;

class APIMessageTest extends TestBase
{
  public function testObject()
  {
    $this->assertInstanceOf(APIMessage::class, new APIMessage([]));
  }

  public function testGetData()
  {
    $data = [];
    $this->assertSame($data, (new APIMessage($data))->getData());
  }

  public function testHasText()
  {
    $object  = new APIMessage(['message' => ['text' => 'test']]);
    $this->assertTrue($object->hasText());
    $this->assertSame('test', $object->getText());
  }

  public function testHasNoText()
  {
    $object  = new APIMessage([]);
    $this->assertFalse($object->hasText());
    $this->assertSame(null, $object->getText());
  }

  public function testGetUpdateId()
  {
    $updateId = 123456;
    $data = ['update_id' => $updateId];
    $this->assertSame($updateId, (new APIMessage($data))->getUpdateId());    
  }
}
