<?php

namespace App\Service\Bot;

use BotMan\BotMan\BotMan;

class ContactBot extends BaseBot
{
    private string $recipient;

    public function __construct(string $token, string $recipient)
    {
        $this->recipient = $recipient;

        parent::__construct($token);
    }

    public function listen(): void
    {
        $this->botMan->hears('/start', function (BotMan $bot) {
            $msg = 'Привіт. Я бот каналу "Сирена Кременчук';
            $msg .= 'Якщо у Вас є питання чи пропозиції з покращення - напишіть, будь ласка, повідомлення, залиште контакти і адміністратор зв\'яжеться з Вами за необхідності';

            $bot->reply($msg);
        });

        $this->botMan->fallback(function (BotMan $botMan) {
            $this->botMan->say(
                $botMan->getUser()->getUsername(),
                $this->recipient
            );
        });

        $this->botMan->listen();
    }
}