<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = (new User())
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setNickName($data['nick_name'])
            ->setAge($data['age'])
            ->setPassword($data['password']);

        $this->userRepository->create($user);

        return new Response('', 201);
    }
}