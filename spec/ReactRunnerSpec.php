<?php

namespace spec\TelegramBot;

use TelegramBot\BotInterface;
use TelegramBot\ReactRunner;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReactRunnerSpec extends ObjectBehavior
{
    function let(\React\EventLoop\LoopInterface $loop) {
      $this->beConstructedWith($loop);
    }

    function it_is_initializable(\React\EventLoop\LoopInterface $loop)
    {
        $this->shouldHaveType(ReactRunner::class);
    }

    /*
    function it_calls_bot_in_a_loop(\React\EventLoop\LoopInterface $loop, $bot)
    {
        $bot->beADoubleOf(BotInterface::class);
        $this->setLoopTimeout(0);
        $bot->poll()->shouldBeCalled();
        $this->runBot($bot, 1);
    }
    */
}
