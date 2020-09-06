<?php
declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\Event;
use App\Repository\EventRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EventHandler implements MessageHandlerInterface
{
    /** @var EventRepository */
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function __invoke(Event $event)
    {
        $this->eventRepository->create($event);
    }
}