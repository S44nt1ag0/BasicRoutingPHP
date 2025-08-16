<?php

namespace SimpleApi\Database;

use PDO;
use PDOException;

class Connection
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            try {

                self::$pdo = new PDO('sqlite:' . __DIR__ . '/../../data/database.sqlite');
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die("" . $e->getMessage());
            }

        }


        return self::$pdo;

    }

}