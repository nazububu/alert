<?php

namespace App\Service\Bot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;

class AlertBot
{
    public BotMan $botMan;

    public function __construct(string $token)
    {
        DriverManager::loadDriver(TelegramDriver::class);

        $this->botMan = BotManFactory::create([
            'telegram' => [
                'token' => $token,
            ],
        ]);
    }

    public function notify(string $identifier, string $message)
    {
        $this->botMan->say($message, $identifier, TelegramDriver::class);
    }
}