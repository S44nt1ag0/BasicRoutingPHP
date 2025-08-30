<?php

namespace SimpleApi\Database;

require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;

use PDO;
use PDOException;

class Connection
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            try {

                $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
                $dotenv->load();

                $DB_HOST = $_ENV['DB_HOST'];
                $DB_USER = $_ENV['DB_USER'];
                $DB_PASS = $_ENV['DB_PASS'];
                $DB_NAME = $_ENV['DB_NAME'];

                self::$pdo = new PDO("mysql:host=$DB_HOST; dbname=$DB_NAME;", username: $DB_USER, password: $DB_PASS);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die("" . $e->getMessage());
            }

        }


        return self::$pdo;

    }

}