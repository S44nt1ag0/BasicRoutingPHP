<?php

namespace SimpleApi\Controllers;
use SimpleApi\Database\Connection;

class UserController
{
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

}


?>