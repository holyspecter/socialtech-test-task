<?php
declare(strict_types=1);

namespace App\Repository;

abstract class AbstractRepository
{
    /** @var string */
    protected $dbPath;

    public function __construct(string $dbPath)
    {
        $this->dbPath = $dbPath;
    }

    protected function getCollection(string $name): array
    {
        $this->createFileIfNotExists($name);

        return json_decode(file_get_contents($this->getFilePath($name)), true);
    }

    protected function writeCollection(string $name, array $data): void
    {
        file_put_contents($this->getFilePath($name), json_encode($data));
    }

    protected function createFileIfNotExists(string $collectionName): void
    {
        if (!file_exists($this->getFilePath($collectionName))) {
            file_put_contents($this->getFilePath($collectionName), json_encode([]));
        }
    }

    protected function getFilePath(string $collectionName): string
    {
        return $this->dbPath . DIRECTORY_SEPARATOR . $collectionName . '.json';
    }
}
