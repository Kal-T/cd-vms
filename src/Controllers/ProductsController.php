<?php

namespace App\Controllers;

use App\Models\Product;
use App\Helpers\ViewHelper;

class ProductsController
{
    private $productModel;
    private $itemsPerPage = 10;

    public function __construct(Product $productModel)
    {
        $this->productModel = $productModel;
    }

    public function listProducts()
    {
        // Pagination logic
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $this->itemsPerPage;

        $products = $this->productModel->getProducts($this->itemsPerPage, $offset);
        $totalProducts = $this->productModel->getTotalProducts();
        $totalPages = ceil($totalProducts / $this->itemsPerPage);

        // Render the view
        ViewHelper::respondOrRender('products/list', [
            'products' => $products,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function viewProduct($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            ViewHelper::respondOrRender('products/view', ['product' => $product]);
        } else {
            http_response_code(404);
            echo 'Product not found';
        }
    }

    public function createProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'image_url' => $_POST['image_url'],
                'quantity_available' => $_POST['quantity_available']
            ];
            $this->productModel->createProduct($data);
            header('Location: /vms/products');
            exit;
        } else {
            ViewHelper::respondOrRender('products/create', []);
        }
    }

    public function updateProduct($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'image_url' => $_POST['image_url'],
                'quantity_available' => $_POST['quantity_available']
            ];
            $this->productModel->updateProduct($id, $data);
            header('Location: /vms/products');
            exit;
        } else {
            $product = $this->productModel->getProductById($id);
            ViewHelper::respondOrRender('products/edit', ['product' => $product]);
        }
    }

    public function deleteProduct($id)
    {
        $this->productModel->deleteProduct($id);
        header('Location: /vms/products');
        exit;
    }
}
