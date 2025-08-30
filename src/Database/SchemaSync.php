<?php

namespace SimpleApi\Database;

use PDO;

class SchemaSync
{
    public static function sync(): void
    {
        $pdo = Connection::getConnection();

        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user VARCHAR(255) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                name VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ";

        $pdo->exec($sql);
    }
}
