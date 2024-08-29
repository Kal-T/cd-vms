<?php

namespace App\Services;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct($dsn, $user, $password)
    {
        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function queryParams($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        // Bind parameters, converting to appropriate types
        foreach ($params as $key => $value) {
            if (is_int($value)) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
