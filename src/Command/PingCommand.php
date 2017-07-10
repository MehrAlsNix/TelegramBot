<?php

namespace TelegramBot\Command;

use TelegramBot\APIMessage;
use TelegramBot\CommandInterface;
use League\Event\EventInterface;

class PingCommand implements CommandInterface
{
    public function isListener($listener)
    {
        return $listener === $this;
    }

    /**
     * @param EventInterface $event
     * @param array $param
     */
    public function handle(EventInterface $event, $param = [])
    {
        $event->stopPropagation();
        /** @var \Telegrambot\APIMessage */
        $message = $param['message'];
        $param['responder']('pong !');
    }
}
