<?php

namespace TelegramBot;

/**
 * message from api
 */
class APIMessage
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function hasText()
    {
        return isset($this->data['message']['text']);
    }

    public function getText()
    {
        if (!$this->hasText()) {
            return null;
        }
        return $this->data['message']['text'];
    }

    public function getResponseData($responseText)
    {
        return [
      'chat_id' => $this->data['message']['chat']['id'],
      'reply_to_message_id' => $this->data['message']['message_id'],
      'text' => $responseText
    ];
    }

    public function getUpdateId()
    {
        return $this->data['update_id'];
    }
}
