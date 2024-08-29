<?php

namespace App\Models;

use App\Services\Database;

class User
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function authenticate($username, $password)
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->query($sql, ['username' => $username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return false;
    }

    public function createUser($data)
    {
        $sql = "INSERT INTO users (username, password_hash, role) VALUES (:username, :password, :role)";
        $this->db->query($sql, $data);
    }
}
