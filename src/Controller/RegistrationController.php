<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController
{
    private $userRepository;

    private $passwordEncoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function registerAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = (new User())
            ->setFirstName($data['first_name'])
            ->setLastName($data['last_name'])
            ->setNickName($data['nick_name'])
            ->setAge($data['age']);

        $user->setPassword($this->passwordEncoder->encodePassword($user, $data['password']));

        $this->userRepository->create($user);

        return new Response('', 201);
    }
}
