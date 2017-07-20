<?php

namespace TelegramBot;

use League\Event\EmitterTrait;
use React\HttpClient\Response;

class Bot implements BotInterface
{
    use EmitterTrait;

    /**
     * @var APIPollClient
     */
    private $client;

    /**
     * @param APIClient|APIPollClient $client
     */
    public function __construct(APIPollClient $client)
    {
        $this->client = $client;
    }

    public function poll()
    {
        $this->client->poll([$this, '_handlePollResponse']);
    }

    /**
     * @param Response $response
     */
    public function _handlePollResponse(Response $response)
    {
        $response->on('data', [$this, '_handlePollData']);
        $response->on('error', [$this, '_handlePollError']);
    }

    /**
     * @param array $data
     * @param Response $response
     */
    public function _handlePollData($data, $response)
    {
        $data = json_decode($data, 1);
        $messageData = $data['result'];

        foreach ($messageData as $message) {
            $apiMessage = new APIMessage($message);
            $this->client->markMessageHandled($apiMessage);

            if (!$apiMessage->hasText()) {
                continue;
            }

            $this->getEmitter()->emit(
                $apiMessage->getText(),
                ['message' => $message, 'responder' => $this->getResponder($apiMessage)]
            );
        }
    }

    /**
     * @param Response $response
     * @throws \Exception
     */
    public function _handlePollError(Response $response)
    {
        throw new \RuntimeException('Not supported.');
    }

    /**
     * @param APIMessage $message
     * @return \Closure|callable
     */
    public function getResponder(APIMessage $message): callable
    {
        return function ($text) use ($message) {
            $this->client->send($text, $message);
        };
    }
}
