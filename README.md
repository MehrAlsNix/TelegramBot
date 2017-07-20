# TelegramBot
Classes for creating a bot for telegram

[![Build Status](https://travis-ci.org/MehrAlsNix/TelegramBot.svg?branch=master)](https://travis-ci.org/MehrAlsNix/TelegramBot)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/MehrAlsNix/TelegramBot/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/MehrAlsNix/TelegramBot/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/MehrAlsNix/TelegramBot/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/MehrAlsNix/TelegramBot/?branch=master)

## Example

```php
#!/usr/bin/env php
<?php
require_once './vendor/autoload.php';

/** @var LoopInterface */
$loop = React\EventLoop\Factory::create();

$runner = new TelegramBot\ReactRunner($loop);

$resolverFactory = new React\Dns\Resolver\Factory();
$resolver = $resolverFactory->create('8.8.8.8', $loop);
$HttpClient = (new React\HttpClient\Factory)->create(
  $loop,
  $resolver
);

$apiClient = new TelegramBot\APIPollClient(getenv('BOT_TOKEN'), $HttpClient);

$bot = new TelegramBot\Bot($apiClient);

$bot->addListener('/ping', new TelegramBot\Command\PingCommand);

$runner->runBot($bot);

```
