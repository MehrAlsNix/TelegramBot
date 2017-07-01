<?php

namespace TelegramBot;

class BotFactory
{

    public function __construct($loop)
    {
      $this->resolverFactory = new \React\Dns\Resolver\Factory();
      $this->resolver = $this->resolverFactory->create('8.8.8.8', $loop);
      $this->HttpClient = (new \React\HttpClient\Factory)->create(
        $loop,
        $this->resolver
      );

    }


    public function createWithToken($token)
    {

      $apiClient = new APIPollClient($token, $this->HttpClient);

      $bot = new Bot($apiClient);

      return $bot;
    }
}
