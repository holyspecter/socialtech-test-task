<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\EventController;
use App\Entity\User;
use App\Message\Event;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

class EventControllerTest extends TestCase
{
    private $securityMock;

    private $messageBusMock;

    protected function setUp()
    {
        $this->securityMock = $this->createMock(Security::class);
        $this->messageBusMock = $this->createMock(MessageBusInterface::class);
    }

    public function testTrackEventDispatchesMessageForAuthenticatedUser()
    {
        $eventData = [
            'source_label' => 'label1',
            'date_created' => (new \DateTime())->format(\DateTime::ISO8601),
        ];
        $requestMock = $this->createMock(Request::class);
        $requestMock->expects($this->atLeastOnce())
            ->method('getContent')
            ->willReturn(json_encode($eventData));

        $user = (new User())
            ->setId(391);
        $this->securityMock->expects($this->atLeastOnce())
            ->method('getUser')
            ->willReturn($user);

        $this->messageBusMock->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (Event $arg) use ($eventData) {
                $this->assertInstanceOf(Event::class, $arg);
                $this->assertEquals(391, $arg->getUserId());
                $this->assertEquals($eventData['source_label'], $arg->getSourceLabel());
                $this->assertEquals($eventData['date_created'], $arg->getDateCreated());

                return true;
            }))
        ->willReturn(new Envelope(new \stdClass()));

        $response = $this->getSUT()->trackEventAction($requestMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    private function getSUT()
    {
        return new EventController($this->securityMock, $this->messageBusMock);
    }
}