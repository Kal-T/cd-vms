<?php

namespace App\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\Key;

class JwtMiddleware
{
    private $jwtKey;

    public function __construct()
    {
        $this->jwtKey = $_ENV['JWT_KEY']; // Ensure this is set correctly
    }

    public function __invoke($request, $response, $next)
    {
        $headers = $request->getHeaders();
        if (!isset($headers['Authorization'][0])) {
            return $response->withStatus(401)->write('Authorization header is missing');
        }

        $authHeader = $headers['Authorization'][0];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            // Decode the JWT token
            $decoded = JWT::decode($token, new Key($this->jwtKey, 'HS256'));
            $request = $request->withAttribute('user', $decoded);
        } catch (ExpiredException $e) {
            return $response->withStatus(401)->write('Token has expired');
        } catch (\Exception $e) {
            return $response->withStatus(401)->write('Invalid token');
        }

        return $next($request, $response);
    }
}

