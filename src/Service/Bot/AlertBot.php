<?php

namespace App\Service\Bot;

class AlertBot extends BaseBot
{
    private string $recipient;

    public function __construct(string $token, string $recipient)
    {
        $this->recipient = $recipient;

        parent::__construct($token);
    }

    public function notify(string $message)
    {
        $this->send($message, $this->recipient);
    }
}