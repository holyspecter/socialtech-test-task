<?php
declare(strict_types=1);

namespace App\Message;

class Event implements \JsonSerializable
{
    /** @var int */
    private $userId;

    /** @var string */
    private $sourceLabel;

    /** @var string */
    private $dateCreated;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getSourceLabel(): string
    {
        return $this->sourceLabel;
    }

    public function setSourceLabel(string $sourceLabel): self
    {
        $this->sourceLabel = $sourceLabel;

        return $this;
    }

    public function getDateCreated(): string
    {
        return $this->dateCreated;
    }

    public function setDateCreated(string $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'user_id' => $this->userId,
            'source_label' => $this->sourceLabel,
            'date_created' => $this->dateCreated,
        ];
    }
}