<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\ViewHelper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UsersController
{
    private $userModel;
    private $viewHelper;

    public function __construct(User $userModel, ViewHelper $viewHelper)
    {
        $this->userModel = $userModel;
        $this->viewHelper = $viewHelper;
    }
    
    public function login()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = $this->userModel->authenticate($username, $password);

        if ($user) {
            $payload = [
                'sub' => $user['id'], // Subject claim (user ID)
                'role' => $user['role'],
                'exp' => time() + 3600 // Token expires in 1 hour
            ];
            $jwt = JWT::encode($payload, $_ENV['JWT_KEY'], 'HS256');

            if (strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0) {
                // API response
                header('Content-Type: application/json');
                echo json_encode(['token' => $jwt]);
                exit;
            } else {
                // Web response
                setcookie('jwt', $jwt, time() + 3600, "/", "", false, true); // Secure and HttpOnly flags recommended
                header('Location: /vms/products');
                exit;
            }
        } else {
            // Handle error for both API and web requests
            if (strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0) {
                // API response
                header('Content-Type: application/json');
                http_response_code(401); // Unauthorized
                echo json_encode(['error' => 'Invalid credentials']);
                exit;
            } else {
                // Web response
                $this->viewHelper->respondOrRender('users/login', ['error' => 'Invalid credentials']);
                return;
            }
        }
    } else {
        // Render login view for GET requests
        $this->viewHelper->respondOrRender('users/login', []);
    }
}


    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $errors = [];

            // Check if username already exists
            if ($this->userModel->usernameExists($username)) {
                $errors[] = 'Username already exists';
            }

            // If there are errors, re-render the registration form with errors
            if (!empty($errors)) {
                $this->viewHelper->respondOrRender('users/register', ['errors' => $errors]);
                return;
            }

            $data = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role' => 'user'
            ];
            $this->userModel->createUser($data);

            // Redirect to login page on success
            if (strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0) {
                // API response
                $this->viewHelper->respondJson(['message' => 'Registration successful, please log in.']);
            } else {
                // Web response
                header('Location: /vms/login');
                exit;
            }
        } else {
            // Display registration form
            $this->viewHelper->respondOrRender('users/register', []);
        }
    }

    public function logout()
    {
        $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;

        if ($isApiRequest) {
            // API response
            // No cookie clearing needed for API requests if JWT is managed via headers
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Logged out']);
        } else {
            // Web response
            // Clear the JWT cookie and end the session
            setcookie('jwt', '', time() - 3600, "/", "", false, true); // Clear the JWT cookie
            session_destroy(); // End the session
            header('Location: /vms/login'); // Redirect to login page
        }
        exit;
    }


    private function handleError($message)
    {
        if (strpos($_SERVER['REQUEST_URI'], '/api') === 0) {
            // API response
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(['message' => $message]);
        } else {
            // Web response
            http_response_code(401);
            echo $message;
        }
        exit;
    }
}
