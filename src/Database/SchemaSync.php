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
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user TEXT NOT NULL UNIQUE,
            email TEXT NOT NULL UNIQUE,
            name TEXT NOT NULL,
            password TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        ";

        $pdo->exec($sql);

    }
}


