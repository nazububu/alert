<?php

namespace App\Controller;

use App\Service\Bot\AlertBot;
use App\Service\Bot\ContactBot;
use App\Service\MessageComposer;
use App\Service\Serializer;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/webhook/', name: 'webhook_')]
class WebhookController extends AbstractController
{
    private array $monitoringRegions = [19, 107];

    private Serializer $serializer;

    private LoggerInterface $logger;

    private MessageComposer $messageComposer;

    private AlertBot $alertBot;

    private ContactBot $contactBot;

    public function __construct(
        Serializer $serializer,
        LoggerInterface $logger,
        MessageComposer $messageComposer,
        AlertBot $alertBot,
        ContactBot $contactBot
    ) {
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->messageComposer = $messageComposer;
        $this->alertBot = $alertBot;
        $this->contactBot = $contactBot;
    }

    #[Route(path: 'alert', name: 'alert')]
    public function alert(Request $request): JsonResponse
    {
        $this->logger->info($this->serializer->serialize($request->toArray()));

        if (
            $request->getPayload()->get('regionId')
            && in_array($request->getPayload()->get('regionId'), $this->monitoringRegions)
        ) {
            $message = $this->messageComposer->composeMessage(
                $request->getPayload()->get('status'),
                $request->getPayload()->get('alarmType')
            );

            $this->alertBot->notify($message);

            $this->logger->info($this->serializer->serialize(['message' => $message]));
        }

        return new JsonResponse();
    }

    #[Route(path: 'telegram', name: 'telegram')]
    public function telegram(): JsonResponse
    {
        $this->contactBot->listen();

        return new JsonResponse(['message' => 'okay']);
    }
}
