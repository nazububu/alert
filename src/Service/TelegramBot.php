<?php

namespace App\Service;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use Psr\Log\LoggerInterface;

class TelegramBot
{
    private string $channel;

    private string $admin;

    private LoggerInterface $logger;

    private Serializer $serializer;

    private BotMan $bot;

    public function __construct(
        string $token,
        string $channel,
        string $admin,
        LoggerInterface $logger,
        Serializer $serializer
    ) {
        $this->channel = $channel;
        $this->admin = $admin;

        DriverManager::loadDriver(TelegramDriver::class);

        $this->bot = BotManFactory::create([
            'telegram' => [
                'token' => $token,
            ],
        ]);

        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    public function notify(string $message): void
    {
        $this->send($this->channel, $message);
    }

    public function handle(): void
    {
        $this->bot->hears('/start', function (BotMan $bot) {
            $msg = 'Привіт. Я бот каналу "Сирена Кременчук';
            $msg .= 'Якщо у Вас є питання чи пропозиції з покращення - напишіть, будь ласка, повідомлення, залиште контакти і адміністратор зв\'яжеться з Вами за необхідності';

            $bot->reply($msg);

            $this->logger->info(
                $this->serializer->serialize([
                    'user'    => $bot->getUser()->getUsername(),
                    'message' => $bot->getMessage()->getPayload(),
                ])
            );
        });

        $this->bot->fallback(function (BotMan $bot) {
            $this->logger->info(
                $this->serializer->serialize([
//                    'user'    => $bot->getUser()->getUsername(),
                    'message' => $bot->getMessage()->getPayload(),
                ])
            );

//            $bot->reply('Дякую за Ваше повідомлення');
//            $bot->say(json_encode(
//                $bot->getMessage()->getPayload(),
//                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
//            ), $this->admin);
        });

        $this->bot->listen();
    }

    private function send(string $recipient, string $message): void
    {
        $this->bot->say($message, $recipient, TelegramDriver::class);
    }
}