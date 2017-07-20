<?php

namespace TelegramBot;

use React\HttpClient\Client;

class APIPollClient
{
    /**
     *
     *
     * @var string
     */
    private $botToken;
    /**
     *
     *
     * @var int
     */
    private $offset;
    /**
     *
     *
     * @var \React\HttpClient\Client
     */
    private $client;

    /**
     * @param string $botToken
     * @param Client $client
     */
    public function __construct(string $botToken, Client $client)
    {
        $this->botToken = $botToken;
        $this->client = $client;
    }

    /**
     * @param CallableInterface $responseHandler
     * @throws \Exception
     */
    public function poll($responseHandler)
    {
        $request = $this->client->request(
            'GET',
            $this->botCommand('getUpdates', ['offset' => $this->offset])
        );
        $request->on('response', $responseHandler);
        $request->on(
            'error', function ($data) {
            throw new \RuntimeException($data);
        }
        );
        $request->end();
    }

    /**
     * @param string $answer
     * @param APIMessage $message
     */
    public function send($answer, APIMessage $message)
    {
        $responseData = $this->postDataEncoder($message->getResponseData($answer));

        $responseCall = $this->client->request(
            'POST',
            $this->botCommand('sendMessage'),
            $this->getResponseHeaders($responseData)
        );

        $responseCall->end($responseData);
    }

    public function markMessageHandled(APIMessage $message)
    {
        $this->offset = $message->getUpdateId() + 1;
    }

    public function getResponseHeaders(string $responseString): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Content-Length' => strlen($responseString)
        ];
    }

    /**
     * @param string $command
     * @param array $params
     * @return string
     */
    private function botCommand(string $command, array $params = []): string
    {
        $args = count($params) ? '?' . $this->postDataEncoder($params) : '';

        return $this->assembleUri($command, $args);
    }

    private function assembleUri($command, $params): string
    {
        return sprintf(
            'https://api.telegram.org/bot%s/%s%s',
            $this->botToken,
            $command,
            $params
        );
    }

    private function postDataEncoder(array $data): string
    {
        $string = '';

        foreach ($data as $k => $v) {
            $string .= $k . '=' . urlencode($v) . '&';
        }

        return $string;
    }
}
