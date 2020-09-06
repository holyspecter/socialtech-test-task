<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class EventController
{
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function trackEventAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->messageBus->dispatch(
            (new Event())
                ->setUserId($data['user_id'])
                ->setSourceLabel($data['source_label'])
                ->setDateCreated($data['date_created'])
        );

        return new Response('', 201);
    }
}