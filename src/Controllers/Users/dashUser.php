<?php

namespace SimpleApi\Controllers\Users;
use SimpleApi\Middlewares\AuthMiddleware;

class DashUser
{

    public function index()
    {
        $userData = AuthMiddleware::authenticate();

        http_response_code(200);
        echo json_encode([
            "id" => $userData['user_id'],
            "email" => $userData['email']
        ]);

    }

}
