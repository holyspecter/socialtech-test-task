<?php
declare(strict_types=1);

namespace App\Entity;


class User implements \JsonSerializable
{
    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $nickName;

    /** @var int */
    private $age;

    /** @var string */
    private $password;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getNickName(): string
    {
        return $this->nickName;
    }

    public function setNickName(string $nickName): self
    {
        $this->nickName = $nickName;

        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'nick_name' => $this->nickName,
            'age' => $this->age,
            'password' => $this->password,
        ];
    }
}