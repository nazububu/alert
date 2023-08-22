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

    private LoggerInterface $alertLogger;

    private LoggerInterface $contactLogger;

    private MessageComposer $messageComposer;

    private AlertBot $alertBot;

    private ContactBot $contactBot;

    private string $alarmKey;

    public function __construct(
        Serializer $serializer,
        LoggerInterface $alertLogger,
        LoggerInterface $contactLogger,
        MessageComposer $messageComposer,
        AlertBot $alertBot,
        ContactBot $contactBot,
        string $alarmKey
    ) {
        $this->serializer = $serializer;
        $this->alertLogger = $alertLogger;
        $this->contactLogger = $contactLogger;
        $this->messageComposer = $messageComposer;
        $this->alertBot = $alertBot;
        $this->contactBot = $contactBot;
        $this->alarmKey = $alarmKey;
    }

    #[Route(path: 'alert', name: 'alert')]
    public function alert(Request $request): JsonResponse
    {
        $this->alertLogger->info($this->serializer->serialize($request->toArray()));

        if ($request->query->get('key', null) !== $this->alarmKey) {
            return new JsonResponse(['message' => 'no key provided']);
        }

        if (
            $request->getPayload()->get('regionId')
            && in_array($request->getPayload()->get('regionId'), $this->monitoringRegions)
        ) {
            $message = $this->messageComposer->composeMessage(
                $request->getPayload()->get('status'),
                $request->getPayload()->get('alarmType')
            );

            $this->alertBot->notify($message);
        }

        return new JsonResponse();
    }

    #[Route(path: 'telegram', name: 'telegram')]
    public function telegram(Request $request): JsonResponse
    {
        $this->contactLogger->info($this->serializer->serialize($request->toArray()));

        $this->contactBot->listen();

        return new JsonResponse(['message' => 'okay']);
    }
}
