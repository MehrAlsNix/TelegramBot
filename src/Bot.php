<?php

namespace TelegramBot;

use React\HttpClient\Client;

class Bot implements BotInterface {

  use \League\Event\EmitterTrait;

  /** @var string */
  private $botToken;
  /** @var int */
  private $offset;
  /** @var React\HttpClient\Client */
  private $client;

  /**
   * @param string $botToken
   */
  public function __construct(string $botToken)
  {
      $this->botToken = $botToken;
  }

  /** @var React\HttpClient\Client */
  public function setClient(Client $client) {
    $this->client = $client;
  }

  public function poll() {
    $request = $this->getMassagesRequest();
    $request->on('response', [$this, '_handlePollResponse']);
    $request->end();
  }

  public function _handlePollResponse($response) {
    $response->on('data', [$this, '_handlePollData']);
    $response->on('error', [$this, '_handlePollError']);
  }

  public function _handlePollData($data, $response) {
    $data = json_decode($data, 1);
    $messageData = $data['result'];

    foreach ($messageData as $message) {

      if (!isset($message['message']['text'])) {
        continue;
      }

      $this->getEmitter()->emit(
        $message['message']['text'],
        ['message' => $message, 'responder' => $this->getResponder($message)]
      );

      $this->setUpdateId($message['update_id']);
    }
  }

  public function _handlePollError($response) {
    print('Error: ');
    print_r($response);
  }

  /**
   *
   */
  public function getResponder($message) {
    return function ($text) use($message){
        $this->sendResponse($text, $message);
    };
  }

  public function getMassagesRequest() {
    return $this->client->request(
      'GET',
      $this->botCommand('getUpdates', ['offset' => $this->offset])
    );
  }

  public function sendResponse(string $responseText, array $incomingMessage) {
    $responseData = $this->postDataEncoder([
      'chat_id' => $incomingMessage['message']['chat']['id'],
      'reply_to_message_id' => $incomingMessage['message']['message_id'],
      'text' => $responseText
    ]);

    $responseCall = $this->client->request(
      'POST',
      $this->botCommand('sendMessage'),
      $this->getResponseHeaders($responseData)
    );

    $responseCall->end($responseData);
  }

  public function setUpdateId($id) {
    $this->offset = $id+1;
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

  private function getResponseHeaders(string $responseString) :array {
    return [
      'Content-Type' =>  'application/x-www-form-urlencoded',
      'Content-Length' => strlen($responseString)
    ];
  }

  private function postDataEncoder(array $data) :string {
    $string = '';

    foreach ($data as $k => $v) {
      $string .= $k . '=' . urlencode($v) . '&';
    }

    return $string;
  }
}
