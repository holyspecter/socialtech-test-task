<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\EventController;
use App\Entity\User;
use App\Message\Event;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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

    private $userRepositoryMock;

    private $jwtManagerMock;

    protected function setUp()
    {
        $this->securityMock = $this->createMock(Security::class);
        $this->messageBusMock = $this->createMock(MessageBusInterface::class);
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->jwtManagerMock = $this->createMock(JWTTokenManagerInterface::class);
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
            ->setId(391)
            ->setFirstName('John');
        $this->securityMock->expects($this->atLeastOnce())
            ->method('getUser')
            ->willReturn($user);

        $this->userRepositoryMock->expects($this->never())
            ->method('createAnonymous');

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

        $this->jwtManagerMock->expects($this->never())
            ->method('create');

        $response = $this->getSUT()->trackEventAction($requestMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testTrackEventDispatchesMessageForAnonymousUser()
    {
        $eventData = [
            'source_label' => 'label2',
            'date_created' => (new \DateTime())->format(\DateTime::ISO8601),
        ];
        $requestMock = $this->createMock(Request::class);
        $requestMock->expects($this->atLeastOnce())
            ->method('getContent')
            ->willReturn(json_encode($eventData));

        $this->securityMock->expects($this->atLeastOnce())
            ->method('getUser')
            ->willReturn(null);

        $user = (new User())
            ->setId(404)
            ->setFirstName('anon.');
        $this->userRepositoryMock->expects($this->once())
            ->method('createAnonymous')
            ->willReturn($user);

        $this->messageBusMock->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (Event $arg) use ($eventData) {
                $this->assertInstanceOf(Event::class, $arg);
                $this->assertEquals(404, $arg->getUserId());
                $this->assertEquals($eventData['source_label'], $arg->getSourceLabel());
                $this->assertEquals($eventData['date_created'], $arg->getDateCreated());

                return true;
            }))
            ->willReturn(new Envelope(new \stdClass()));

        $this->jwtManagerMock->expects($this->once())
            ->method('create')
            ->with($user)
            ->willReturn('jwt_token_be_here');

        $response = $this->getSUT()->trackEventAction($requestMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('{"token":"jwt_token_be_here"}', $response->getContent());
    }

    private function getSUT()
    {
        return new EventController(
            $this->securityMock,
            $this->messageBusMock,
            $this->userRepositoryMock,
            $this->jwtManagerMock
        );
    }
}