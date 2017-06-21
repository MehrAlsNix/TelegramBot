# TelegramBot
Classes for creating a bot for telegram

[![Build Status](https://travis-ci.org/MehrAlsNix/TelegramBot.svg?branch=master)](https://travis-ci.org/MehrAlsNix/TelegramBot)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/MehrAlsNix/TelegramBot/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/MehrAlsNix/TelegramBot/?branch=master)[![Code Coverage](https://scrutinizer-ci.com/g/MehrAlsNix/TelegramBot/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/MehrAlsNix/TelegramBot/?branch=master)[![Build Status](https://scrutinizer-ci.com/g/MehrAlsNix/TelegramBot/badges/build.png?b=master)](https://scrutinizer-ci.com/g/MehrAlsNix/TelegramBot/build-status/master)
## Example

```
$bot = new TelegramBot\Bot("BOT_TOKEN");
$bot->addListener('/ping', new TelegramBot\Command\PingCommand);

$runner = TelegramBot\ReactRunner::create();
$runner->runBot($bot);

```
