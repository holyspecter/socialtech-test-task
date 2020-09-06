<?php
declare(strict_types=1);

namespace App\EventListener;

use App\MessageHandler\EventHandler;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class KernelTerminateListener
{
    /** @var InMemoryTransport */
    private $messengerTransport;

    /** @var EventHandler */
    private $messageHandler;

    public function __construct(InMemoryTransport $messengerTransport, EventHandler $messageHandler)
    {
        $this->messengerTransport = $messengerTransport;
        $this->messageHandler = $messageHandler;
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        $messages = $this->messengerTransport->getSent();
        $handler = $this->messageHandler;
        foreach ($messages as $message) {
            $handler($message->getMessage());
        }
    }
}
