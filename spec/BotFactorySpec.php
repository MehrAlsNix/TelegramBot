<?php

namespace spec\TelegramBot;

use TelegramBot\BotInterface;
use TelegramBot\BotFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BotFactorySpec extends ObjectBehavior
{
    function let(\React\EventLoop\LoopInterface $loop) {
      $this->beConstructedWith($loop);
    }

    function it_is_initializable(\React\EventLoop\LoopInterface $loop)
    {
        $this->shouldHaveType(BotFactory::class);
    }

    function it_will_create_a_bot_with_token(\React\EventLoop\LoopInterface $loop)
    {
      $this->createWithToken('BOT_TOKEN')->shouldImplement(BotInterface::class);
    }
}
