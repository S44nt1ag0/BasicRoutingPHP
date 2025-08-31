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

    public function deleteUser(int $id)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function isAdmin(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT is_admin FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (bool) $result['is_admin'] : false;
    }

    public function userExists(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE id = :id AND deleted_at IS NULL");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
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
