<?php

namespace TelegramBot;

use TelegramBot\APIMessage;
use React\HttpClient\Client;
use React\HttpClient\Request;

class APIPollClient
{
  /** @var string */
  private $botToken;
  /** @var int */
  private $offset;
  /** @var \React\HttpClient\Client */
  private $client;

  /**
   * @param string $botToken
   */
  public function __construct(string $botToken, Client $client)
  {
      $this->botToken = $botToken;
      $this->client = $client;
  }

  /**
   * @param CallableInterface $responseInterface
   */
  public function poll($responseHandler) {
    $request = $this->client->request(
      'GET',
      $this->botCommand('getUpdates', ['offset' => $this->offset])
    );
    $request->on('response', $responseHandler);
    #$request->on('error', [$this, 'handleRequestError']);
    $request->end();
  }

  /**
   * @param string $answer
   * @param APIMessage $message
   */
  public function send($answer, APIMessage $message) {
    $responseData = $this->postDataEncoder($message->getResponseData($answer));

    $responseCall = $this->client->request(
      'POST',
      $this->botCommand('sendMessage'),
      $this->getResponseHeaders($responseData)
    );

    $responseCall->end($responseData);
  }

  public function markMessageHandled(APIMessage $message) {
    $this->offset = $message->getUpdateId();
  }

  public function getResponseHeaders(string $responseString) :array {
    return [
      'Content-Type' =>  'application/x-www-form-urlencoded',
      'Content-Length' => strlen($responseString)
    ];
  }


  private function botCommand(string $command, array $params = []) :string {
    $params = (count($params)) ? '?' . $this->postDataEncoder($params) : '';

    return $this->assembleUri($command, $params);
  }


  private function assembleUri($command, $params) :string {
    return sprintf(
      'https://api.telegram.org/bot%s/%s%s',
      $this->botToken,
      $command,
      $params
    );
  }

  private function postDataEncoder(array $data) :string {
    $string = '';

    foreach ($data as $k => $v) {
      $string .= $k . '=' . urlencode($v) . '&';
    }

    return $string;
  }

}
