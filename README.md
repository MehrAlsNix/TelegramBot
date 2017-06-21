# TelegramBot
Classes for creating a bot for telegram

[![Build Status](https://travis-ci.org/MehrAlsNix/TelegramBot.svg?branch=master)](https://travis-ci.org/MehrAlsNix/TelegramBot)

## Example

```
$bot = new TelegramBot\Bot("BOT_TOKEN");
$bot->addListener('/ping', new TelegramBot\Command\PingCommand);

$runner = TelegramBot\ReactRunner::create();
$runner->runBot($bot);

```
