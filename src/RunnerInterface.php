<?php

namespace TelegramBot;

interface RunnerInterface {
  public function runBot(BotInterface $bot, $times = null);
}
