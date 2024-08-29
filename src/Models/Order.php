<?php

namespace App\Models;

use App\Services\Database;

class Order
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function createOrder(array $data)
    {
        $sql = "INSERT INTO orders (user_id, product_id, quantity, total_amount, order_date, status) 
                VALUES (:user_id, :product_id, :quantity, :total_amount, NOW(), :status)";
        $this->db->query($sql, $data);
        return $this->db->lastInsertId(); // Assuming a method to get the last inserted ID
    }

    public function getOrderById($id)
    {
        $sql = "
        SELECT orders.*, transactions.id as transaction_id, transactions.amount, transactions.transaction_date
        FROM orders
        LEFT JOIN transactions ON orders.id = transactions.order_id
        WHERE orders.id = :id
    ";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch();
    }

    public function getAllOrders()
    {
        $sql = "SELECT * FROM orders";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
