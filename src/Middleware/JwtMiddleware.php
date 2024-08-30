<?php

namespace App\Middleware;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware
{
    private $jwtKey;

    public function __construct($jwtKey)
    {
        $this->jwtKey = $jwtKey;
    }

    public function validateJwtCookie()
    {
        $jwtToken = $_COOKIE['jwt'] ?? '';

        if ($jwtToken) {
            try {
                $decoded = JWT::decode($jwtToken, new Key($this->jwtKey, 'HS256'));
                $_SESSION['user_id'] = $decoded->sub;
                $_SESSION['role'] = $decoded->role;
            } catch (Exception $e) {
                // Handle token errors
                setcookie('jwt', '', time() - 3600, "/", "", false, true); // Clear the cookie
                http_response_code(401);
                echo 'Unauthorized: ' . $e->getMessage();
                exit;
            }
        } else {
            // No token, redirect to login
            header('Location: /vms/login');
            exit;
        }
    }

    public function validateJwtToken()
    {
        // $jwtToken = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $jwtToken = $this->getAuthorizationHeader();
        $jwtToken = str_replace('Bearer ', '', $jwtToken);

        if ($jwtToken) {
            try {
                $decoded = JWT::decode($jwtToken, new Key($this->jwtKey, 'HS256'));
                $_SESSION['user_id'] = $decoded->sub;
                $_SESSION['role'] = $decoded->role;
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized: ' . $e->getMessage()]);
                exit;
            }
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized: No token provided']);
            exit;
        }
    }

    private function getAuthorizationHeader() {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            return $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            return $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } elseif (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            return isset($headers['Authorization']) ? $headers['Authorization'] : '';
        }
        return '';
    }
}
