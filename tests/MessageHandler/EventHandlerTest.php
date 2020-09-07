<?php
declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Message\Event;
use App\MessageHandler\EventHandler;
use App\Repository\EventRepository;
use PHPUnit\Framework\TestCase;

class EventHandlerTest extends TestCase
{
    private $eventRepositoryMock;

    protected function setUp()
    {
        $this->eventRepositoryMock = $this->createMock(EventRepository::class);
    }

    public function testInvoke()
    {
        $event = new Event();
        $this->eventRepositoryMock->expects($this->once())
            ->method('create')
            ->with($event);

        (new EventHandler($this->eventRepositoryMock))($event);
    }
}