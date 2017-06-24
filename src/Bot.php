<?php

namespace TelegramBot;

use TelegramBot\APIMessage;

class Bot implements BotInterface
{
    use \League\Event\EmitterTrait;

  /** @var string */
  private $botToken;
  /** @var int */
  private $offset;
  /** @var APIPollClient */
  private $client;

  /**
   * @param APIClient $client
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
   * @param \React\HttpClient\Response $response
   */
  public function _handlePollResponse(\React\HttpClient\Response $response)
  {
      $response->on('data', [$this, '_handlePollData']);
      $response->on('error', [$this, '_handlePollError']);
  }

  /**
   * @param array $data
   * @param \React\HttpClient\Response $response
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
   * @param \React\HttpClient\Response $response
   */
  public function _handlePollError(\React\HttpClient\Response $response)
  {
      throw new \Exception();
  }

  /**
   * @param APIMessage $message
   * @return function
   */
  public function getResponder(APIMessage $message)
  {
      return function ($text) use ($message) {
          $this->client->send($text, $message);
      };
  }
}
