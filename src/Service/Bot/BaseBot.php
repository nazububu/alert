<?php

namespace App\Service\Bot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;

abstract class BaseBot
{
    public string $token;

    public BotMan $botMan;

    public function __construct(string $token)
    {
        DriverManager::loadDriver(TelegramDriver::class);

        $this->token = $token;
        $this->botMan = BotManFactory::create([
            'telegram' => [
                'token' => $this->token,
            ],
        ]);
    }

    public function send(string $message, string $recipient): void
    {
        $this->botMan->say($message, $recipient, TelegramDriver::class);
    }
}