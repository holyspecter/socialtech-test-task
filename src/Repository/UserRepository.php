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

        $users[] = $user;

        $this->writeCollection(self::COLLECTION_NAME, $users);
    }
}