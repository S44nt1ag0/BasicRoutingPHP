<?php

namespace SimpleApi\Controllers\Users;

use SimpleApi\Middlewares\AuthMiddleware;
use SimpleApi\Repositories\UserRepository;

class DeleteUser
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
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

}

