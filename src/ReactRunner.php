<?php
namespace TelegramBot;

use React\EventLoop\Factory as EventLoopFactory;

use React\EventLoop\LoopInterface;

/**
 * @class TelegramBot\ReactRunner
 *
 * runner for a bot using react event loop
 */
class ReactRunner implements RunnerInterface
{
    public static function create() :ReactRunner
    {
        /** @var LoopInterface */
      $loop = EventLoopFactory::create();

        return new self($loop);
    }

    /** @var LoopInterface */
    private $loop;
    /** @var int */
    private $loopTimeout = 2;
    /** @var int */
    private $loopCounter = 0;


    /**
     * @param \React\EventLoop\LoopInterface
     * @param \React\HttpClient\Client
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    public function setLoopTimeout(int $timeout)
    {
      $this->loopTimeout = $timeout;
    }

    /**
     * @param BotInterface
     * @param integer $maxTimesToPoll
     */
    public function runBot(BotInterface $bot, $maxTimesToPoll = null)
    {
        $this->loopCounter = 0;

        $this->loop->addPeriodicTimer(
          $this->loopTimeout,
          function (\React\EventLoop\Timer\Timer $timer) use ($bot, $maxTimesToPoll) {
            $bot->poll();
            $this->loopCounter++;
            if (!is_null($maxTimesToPoll) && ($maxTimesToPoll >= $this->loopCounter)) {
                $this->loop->cancelTimer($timer);
            }
        });

        $this->loop->run();
    }
}
