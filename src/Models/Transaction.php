<?php

namespace App\Models;

use App\Services\Database;

class Transaction
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAllTransactions()
    {
        $stmt = $this->db->query("SELECT * FROM transactions");
        return $stmt->fetchAll();
    }

    public function createTransaction(array $data)
    {
        $sql = "INSERT INTO transactions (order_id, amount, payment_method, transaction_date, status) 
                VALUES (:order_id, :amount, :payment_method, NOW(), :status)";
        $this->db->query($sql, $data);
    }

    public function getTransactionById($id)
    {
        $sql = "SELECT * FROM transactions WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch();
    }
}
