<?php

namespace SimpleApi\Controllers;
use SimpleApi\Repositories\UserRepository;
use SimpleApi\Middlewares\AuthMiddleware;

use Firebase\JWT\JWT;

class UserController
{
    private string $jwt_secret = 'chaveSecreta@2025';
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function deleteUser()
    {
        AuthMiddleware::onlyAdmin();

        $input = json_decode(file_get_contents("php://input"), true);

        if (
            !isset($input['id'])
        ) {
            http_response_code(400);
            echo json_encode(["error" => "Required fields: user, email, name, password"]);
            return;
        }

        try {

            $userExists = $this->userRepository->userExists($input["id"]);

            if (!$userExists) {
                http_response_code(400);
                echo json_encode(["error" => "User not found"]);
                return;
            }

            $deteUser = $this->userRepository->deleteUser($input["id"]);

            if (!$deteUser) {
                throw new \Exception("Failed to delete user");
            }

            http_response_code(201);
            echo json_encode(["message" => "user deleted successfully"]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error deleting user."]);
        }

    }

    public function createUser()
    {
        $input = json_decode(file_get_contents("php://input"), true);

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

        try {
            $success = $this->userRepository->createUser($input);

            if (!$success) {
                throw new \Exception("Failed to create user");
            }

            http_response_code(201);
            echo json_encode(["message" => "User created successfully"]);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Error creating user."]);
        }
    }

    public function index()
    {
        http_response_code(200);
        echo json_encode(["hello" => "world"]);
    }

    public function login()
    {

        $input = json_decode(file_get_contents("php://input"), associative: true);

        if (isset($input["email"]) && isset($input["passworld"])) {
            http_response_code(400);
            echo json_encode(["error" => "Required fields: email, password"]);
            return;
        }

        $user = $this->userRepository->findByEmail($input['email']);

        if (!$user || !password_verify($input['password'], $user->getPassword())) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid email or password"]);
            return;
        }

        if ($user->getDeletedAt() !== null) {
            http_response_code(401);
            echo json_encode(["error" => "account deleted"]);
            return;
        }

        $payload = [
            'user_id' => $user->getId(),
            'email' => $user->getEmail(),
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