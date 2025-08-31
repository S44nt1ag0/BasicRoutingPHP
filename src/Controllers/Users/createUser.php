<?php


namespace SimpleApi\Controllers\Users;
use SimpleApi\Repositories\UserRepository;

class CreateUser
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function index()
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
}


?>