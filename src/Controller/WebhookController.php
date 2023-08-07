<?php

namespace App\Controller;

use App\Service\MessageComposer;
use App\Service\TelegramBot;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/webhook/', name: 'webhook_')]
class WebhookController extends AbstractController
{
    private array $monitoringRegions = [19, 107];

    private LoggerInterface $logger;

    private MessageComposer $messageComposer;

    private TelegramBot $telegramBot;

    public function __construct(
        LoggerInterface $logger,
        MessageComposer $messageComposer,
        TelegramBot $telegramBot
    ) {
        $this->logger = $logger;
        $this->messageComposer = $messageComposer;
        $this->telegramBot = $telegramBot;
    }

    #[Route(path: 'alert', name: 'alert', methods: ['POST'])]
    public function alert(Request $request): JsonResponse
    {
        if (
            $request->getPayload()->get('regionId')
            && in_array($request->getPayload()->get('regionId'), $this->monitoringRegions)
        ) {
            $message = $this->messageComposer->composeMessage(
                $request->getPayload()->get('status'),
                $request->getPayload()->get('type')
            );

            $this->telegramBot->notify($message);
        }

        return new JsonResponse();
    }

    #[Route(path: 'telegram', name: 'telegram', methods: ['POST'])]
    public function telegram(Request $request): JsonResponse
    {
        $this->logger->info(json_encode($request->toArray()));

        return new JsonResponse(['message' => 'okay']);
    }
}
