<?php

namespace TelegramBot\Command;

use League\Event\EventInterface;
use TelegramBot\CommandInterface;

class PingCommand implements CommandInterface
{
    public function isListener($listener): bool
    {
        return $listener === $this;
    }

    /**
     * @param EventInterface $event
     * @param array $param
     */
    public function handle(EventInterface $event, array $param = [])
    {
        $event->stopPropagation();
        /** @var \Telegrambot\APIMessage */
        $message = $param['message'];
        $param['responder']('pong !');
    }
}
