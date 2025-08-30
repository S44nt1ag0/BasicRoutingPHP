<?php

namespace SimpleApi\Models;

class User
{
    private ?int $id;
    private string $user;
    private string $email;
    private string $name;
    private string $password;

    public function __construct(
        ?int $id,
        string $user,
        string $email,
        string $name,
        string $password,
        bool $is_admin
    ) {
        $this->id = $id;
        $this->user = $user;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->is_admin = $is_admin;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getIsAdmin(): bool
    {
        return $this->is_admin;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password,
            'is_admin' => $this->is_admin,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['user'],
            $data['email'],
            $data['name'],
            $data['password'],
            $data['is_admin'] ?? false
        );
    }
}
