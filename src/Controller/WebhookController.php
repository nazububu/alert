<?php

namespace App\Controller;

use App\Entity\Subscriber;
use App\Repository\SubscriberRepository;
use App\Service\Bot\AlertBot;
use App\Service\MessageComposer;
use App\Service\Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/webhook/', name: 'webhook_')]
class WebhookController extends AbstractController
{
    private Serializer $serializer;

    private LoggerInterface $alertLogger;

    private SubscriberRepository $subscriberRepository;

    private MessageComposer $messageComposer;

    private AlertBot $alertBot;

    private string $alarmKey;

    public function __construct(
        Serializer $serializer,
        LoggerInterface $alertLogger,
        SubscriberRepository $subscriberRepository,
        MessageComposer $messageComposer,
        AlertBot $alertBot,
        string $alarmKey
    ) {
        $this->serializer = $serializer;
        $this->alertLogger = $alertLogger;
        $this->subscriberRepository = $subscriberRepository;
        $this->messageComposer = $messageComposer;
        $this->alertBot = $alertBot;
        $this->alarmKey = $alarmKey;
    }

    #[Route(path: 'alert', name: 'alert')]
    public function alert(Request $request): JsonResponse
    {
        $this->alertLogger->info($this->serializer->serialize($request->toArray()));

        if ($request->query->get('key', null) !== $this->alarmKey) {
            return new JsonResponse(['message' => 'Forbidden']);
        }

        if ($request->getPayload()->get('regionId')) {
            /** @var ArrayCollection<Subscriber> $subscribers */
            $subscribers = new ArrayCollection($this->subscriberRepository->findAll());

            foreach ($subscribers as $subscriber) {
                if (!in_array($request->getPayload()->get('regionId'), $subscriber->getRegions())) {
                    continue;
                }

                $this->alertBot->notify(
                    $subscriber->getIdentifier(),
                    $this->messageComposer->composeMessage(
                        $request->getPayload()->get('status'),
                        $request->getPayload()->get('alarmType')
                    )
                );
            }
        }

        return new JsonResponse(['message' => 'Ok']);
    }
}
