<?php

namespace spec\TelegramBot;

use TelegramBot\Bot;
use TelegramBot\APIPollClient;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BotSpec extends ObjectBehavior
{
    function it_is_initializable(APIPollClient $apiClient)
    {
        $this->beConstructedWith($apiClient);
        $this->shouldHaveType(Bot::class);
    }
}
