<?php

namespace SimpleApi\Repositories;

use SimpleApi\Database\Connection;
use SimpleApi\Models\User;

use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    public function createUser(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (user, email, name, password) VALUES (:user, :email, :name, :password)"
        );

        return $stmt->execute([
            ':user' => $data['user'],
            ':email' => $data['email'],
            ':name' => $data['name'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);

    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? User::fromArray($data) : null;
    }


}
