<?php

namespace App\Service;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;

class TelegramBot
{
    private string $recipient;

    private BotMan $bot;

    public function __construct(string $token, string $recipient)
    {
        $this->recipient = $recipient;

        DriverManager::loadDriver(TelegramDriver::class);

        $this->bot = BotManFactory::create([
            'telegram' => [
                'token' => $token,
            ],
        ]);
    }

    public function notify(string $message): void
    {
        $this->bot->say($message, $this->recipient, TelegramDriver::class);
    }
}