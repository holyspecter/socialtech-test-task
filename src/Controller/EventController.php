<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

class EventController
{
    /** @var Security */
    private $security;

    /** @var MessageBusInterface  */
    private $messageBus;

    public function __construct(Security $security, MessageBusInterface $messageBus)
    {
        $this->security = $security;
        $this->messageBus = $messageBus;
    }

    public function trackEventAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $userId = $this->security->getUser()
            ? $this->security->getUser()->getId()
            : 404; // @todo: handle non-authenticated users better
        $this->messageBus->dispatch(
            (new Event())
                ->setUserId($userId)
                ->setSourceLabel($data['source_label'])
                ->setDateCreated($data['date_created'])
        );

        return new Response('', 201);
    }
}