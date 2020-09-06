<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

class UserRepository extends AbstractRepository
{
    private const COLLECTION_NAME = 'users';

    public function create(User $user): void
    {
        $users = $this->getCollection(self::COLLECTION_NAME);

        $user->setId(count($users) + 1); // @todo: better id generation

        $users[] = $user;

        $this->writeCollection(self::COLLECTION_NAME, $users);
    }

    public function findOneByNickName(string $nickName): ?User
    {
        $users = $this->getCollection(self::COLLECTION_NAME);

        foreach ($users as $user) {
            if ($nickName === $user['nick_name']) {
                return (new User())
                    ->setId($user['id'])
                    ->setNickName($user['nick_name'])
                    ->setFirstName($user['first_name'])
                    ->setLastName($user['last_name'])
                    ->setAge($user['age'])
                    ->setPassword($user['password']);
            }
        }

        return null;
    }
}