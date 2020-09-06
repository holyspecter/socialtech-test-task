<?php
declare(strict_types=1);

namespace App\Controller;

use App\Message\Event;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

class EventController
{
    /** @var Security */
    private $security;

    /** @var MessageBusInterface  */
    private $messageBus;

    /** @var UserRepository */
    private $userRepository;

    /** @var JWTTokenManagerInterface */
    private $jwtManager;

    public function __construct(
        Security $security,
        MessageBusInterface $messageBus,
        UserRepository $userRepository,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->security = $security;
        $this->messageBus = $messageBus;
        $this->userRepository = $userRepository;
        $this->jwtManager = $jwtManager;
    }

    public function trackEventAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->security->getUser() ?? $this->userRepository->createAnonymous();
        $this->messageBus->dispatch(
            (new Event())
                ->setUserId($user->getId())
                ->setSourceLabel($data['source_label'])
                ->setDateCreated($data['date_created'])
        );

        $responseData = '';
        if ('anon.' === $user->getFirstName()) {
            $responseData = [
                'token' => $this->jwtManager->create($user)
            ];
        }

        return new JsonResponse($responseData, 201);
    }
}