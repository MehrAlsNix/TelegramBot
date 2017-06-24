<?php
namespace TelegramBot;

use React\EventLoop\Factory as EventLoopFactory;

use React\EventLoop\LoopInterface;

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

    /**
     * @param \React\EventLoop\LoopInterface
     * @param \React\HttpClient\Client
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    /**
     * @param BotInterface
     * @param integer $maxTimesToPoll
     */
    public function runBot(BotInterface $bot, $maxTimesToPoll = null)
    {
        $counter = 0;

        $this->loop->addPeriodicTimer(2, function (\React\EventLoop\Timer\Timer $timer) use ($bot, $maxTimesToPoll, &$counter) {
            $bot->poll();

            if (!is_null($maxTimesToPoll) && ($maxTimesToPoll >= $counter)) {
                $this->loop->cancelTimer($timer);
            }
        });

        $this->loop->run();
    }
}
