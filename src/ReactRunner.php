<?php
namespace TelegramBot;

use React\HttpClient\Client;
use React\EventLoop\LoopInterface;

class ReactRunner implements RunnerInterface
{


    public static function create() :ReactRunner
    {
      $loop = \React\EventLoop\Factory::create();

      $resolver = (new \React\Dns\Resolver\Factory)->create('8.8.8.8', $loop);
      $client = (new \React\HttpClient\Factory)->create($loop, $resolver);

      return new self($loop, $client);
    }

    /** @var LoopInterface */
    private $loop;
    /** @var Client */
    private $client;

    /**
     * @param \React\EventLoop\LoopInterface
     * @param \React\HttpClient\Client
     */
    public function __construct(LoopInterface $loop, Client $client)
    {
      $this->loop = $loop;
      $this->client = $client;
    }

    /**
     * @param BotInterface
     * @param integer $times
     */
    public function runBot(BotInterface $bot, $times = null)
    {
      $counter = 0;
      $bot->setClient($this->client);

      $this->loop->addPeriodicTimer(2, function (\React\EventLoop\Timer\Timer $timer) use ($bot, $times, &$counter) {

        $bot->poll();

        if ( $times && $times >= $counter) {
          $this->loop->cancelTimer($timer);
        }
      });

      $this->loop->run();
    }

}
