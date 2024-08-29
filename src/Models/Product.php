<?php

namespace App\Models;

use App\Services\Database;

class Product
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getAllProducts()
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProducts($limit, $offset)
    {
        $query = "SELECT id, name, price, quantity_available, image_url FROM products LIMIT :limit OFFSET :offset";
        $stmt = $this->db->queryParams($query, ['limit' => $limit, 'offset' => $offset]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTotalProducts()
    {
        $query = "SELECT COUNT(*) FROM products";
        $stmt = $this->db->query($query);
        return (int)$stmt->fetchColumn();
    }

    public function getProductById($id)
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->query($sql, ['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function createProduct($data)
    {
        $sql = "INSERT INTO products (name, price, image_url, quantity_available) VALUES (:name, :price, :image_url, :quantity_available)";
        $this->db->query($sql, $data);
    }

    public function updateProduct($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE products SET name = :name, price = :price, image_url = :image_url, quantity_available = :quantity_available WHERE id = :id";
        $this->db->query($sql, $data);
    }

    public function updateProductQuantity($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE products SET quantity_available = :quantity_available WHERE id = :id";
        $this->db->query($sql, $data);
    }

    public function deleteProduct($id)
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }
}
