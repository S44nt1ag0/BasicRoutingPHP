<?php

namespace SimpleApi\Controllers;
use SimpleApi\Database\Connection;
use SimpleApi\Middlewares\AuthMiddleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController
{
    private string $jwt_secret = 'chaveSecreta@2025';

    public function createUser()
    {
        $pdo = Connection::getConnection();
        $input = json_decode(file_get_contents("php://input"), associative: true);

        if (
            !isset($input['user']) ||
            !isset($input['email']) ||
            !isset($input['name']) ||
            !isset($input['password'])
        ) {
            http_response_code(400);
            echo json_encode(["error" => "Required fields: user, email, name, password"]);
            return;
        }

        $stmt = $pdo->prepare("INSERT INTO users (user, email, name, password) VALUES (:user, :email, :name, :password)");

        try {

            $stmt->execute([
                ':user' => $input['user'],
                ':email' => $input['email'],
                ':name' => $input['name'],
                ':password' => password_hash($input['password'], PASSWORD_BCRYPT)
            ]);

        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error creating user."]);
            return;
        }

        http_response_code(201);
        echo json_encode(["message" => "User created successfully"]);

    }

    public function index()
    {
        http_response_code(200);
        echo json_encode(["hello" => "world"]);
    }

    public function authenticate()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Authorization header not found"]);
            exit;
        }

        $jwt = str_replace('Bearer ', '', $headers['Authorization']);

        try {

            $decoded = JWT::decode($jwt, new Key($this->jwt_secret, 'HS256'));
            return (array) $decoded;

        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid token"]);
            exit;
        }

    }

    public function login()
    {
        $pdo = Connection::getConnection();
        $input = json_decode(file_get_contents("php://input"), associative: true);

        if (isset($input["email"]) && isset($input["passworld"])) {
            http_response_code(400);
            echo json_encode(["error" => "Required fields: email, password"]);
            return;
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $input['email']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($input['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid email or password"]);
            return;
        }

        $payload = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'exp' => time() + 3600
        ];

        $jwt = JWT::encode($payload, $this->jwt_secret, 'HS256');
        echo json_encode(["token" => $jwt]);

    }

    public function dashBoard()
    {
        $userData = AuthMiddleware::authenticate();

        http_response_code(200);
        echo json_encode([
            "id" => $userData['user_id'],
            "email" => $userData['email']
        ]);
    }

}


?>