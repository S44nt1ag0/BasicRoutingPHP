<?php

namespace SimpleApi\Controllers\Users;

use SimpleApi\Repositories\UserRepository;
use Firebase\JWT\JWT;

class LoginUser
{
    private UserRepository $userRepository;
    private string $jwt_secret = 'chaveSecreta@2025';

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
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

}