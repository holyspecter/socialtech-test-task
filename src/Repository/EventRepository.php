<?php
declare(strict_types=1);

namespace App\Repository;

use App\Message\Event;
use SocialTech\StorageInterface;

class EventRepository extends AbstractRepository
{
    private const COLLECTION_NAME = 'events';

    /** @var StorageInterface */
    private $storage;

    public function __construct(string $dbPath, StorageInterface $storage)
    {
        parent::__construct($dbPath);

        $this->storage = $storage;
    }

    public function create(Event $event): void
    {
        $this->createFileIfNotExists(self::COLLECTION_NAME);

        $events = json_decode($this->storage->load($this->getFilePath(self::COLLECTION_NAME)), true);

        $events[] = $event;

        $this->storage->store($this->getFilePath(self::COLLECTION_NAME), json_encode($events));
    }
}