<?php

namespace SimpleApi\Middlewares;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    private static $jwt_secret = "chaveSecreta@2025";
    public static function onlyAdmin(): array
    {
        $userData = self::authenticate();

        if (empty($userData['is_admin']) || !$userData['is_admin']) {
            http_response_code(403);
            echo json_encode(["error" => "Access denied: Admins only"]);
            exit;
        }

        return $userData;
    }

    public static function authenticate(): array
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Authorization header not found"]);
            exit;
        }

        $authHeader = $headers['Authorization'];
        if (strpos($authHeader, 'Bearer ') !== 0) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid Authorization header format"]);
            exit;
        }

        $token = substr($authHeader, 7);

        try {
            $decoded = JWT::decode($token, new Key(self::$jwt_secret, 'HS256'));
            return (array) $decoded;
        } catch (\Exception $e) {
            http_response_code(401);
            echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
            exit;
        }
    }
}

?>