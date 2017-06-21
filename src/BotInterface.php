<?php

namespace TelegramBot;

interface BotInterface {
  /** @var \React\HttpClient\Client */
  public function setClient(\React\HttpClient\Client $client);
  public function poll();
}
