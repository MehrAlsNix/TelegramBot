<?php

namespace TelegramBot;

use React\Dns\Resolver\Factory;
use React\HttpClient\Factory as HttpClientFactory;

class BotFactory
{
    private $HttpClient;
    private $resolver;
    private $resolverFactory;

    /**
     * BotFactory constructor.
     *
     * @param $loop
     */
    public function __construct($loop)
    {
        $this->resolverFactory = new Factory();
        $this->resolver = $this->resolverFactory->create('8.8.8.8', $loop);
        $this->HttpClient = (new HttpClientFactory)->create(
            $loop,
            $this->resolver
        );

    }

    /**
     * @param string $token
     * @return Bot
     */
    public function createWithToken(string $token): Bot
    {

        $apiClient = new APIPollClient($token, $this->HttpClient);

        return new Bot($apiClient);
    }
}
