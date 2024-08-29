<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\ViewHelper;

class UsersController
{
    private $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user = $this->userModel->authenticate($username, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header('Location: /vms/products');
                exit;
            } else {
                echo 'Invalid credentials';
            }
        } else {
            ViewHelper::respondOrRender('users/login', []);
        }
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
                'role' => 'user'
            ];
            $this->userModel->createUser($data);
            header('Location: /vms/login');
            exit;
        } else {
            ViewHelper::respondOrRender('users/register', []);
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /vms/login');
        exit;
    }
}
