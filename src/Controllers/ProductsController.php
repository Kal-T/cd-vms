<?php

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Product;
use App\Helpers\ViewHelper;
use Exception;

class ProductsController
{
    private $productModel;
    private $viewHelper;
    private $response;
    private $itemsPerPage = 10;

    public function __construct(Product $productModel, ViewHelper $viewHelper, Response $response)
    {
        $this->productModel = $productModel;
        $this->viewHelper = $viewHelper;
        $this->response = $response;
    }

    public function listProducts()
    {
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $this->itemsPerPage;

        $products = $this->productModel->getProducts($this->itemsPerPage, $offset);
        $totalProducts = $this->productModel->getTotalProducts();
        $totalPages = ceil($totalProducts / $this->itemsPerPage);

        $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;

        if ($isApiRequest) {
            $this->viewHelper->respondJson([
                'products' => $products,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages
            ]);
        } else {
            $this->viewHelper->respondOrRender('products/list', [
                'products' => $products,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages
            ]);
        }
    }


    public function viewProduct($id)
    {
        $product = $this->productModel->getProductById($id);
        $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;

        if ($product) {
            if ($isApiRequest) {
                $this->viewHelper->respondJson(['product' => $product]);
            } else {
                $this->viewHelper->respondOrRender('products/view', ['product' => $product]);
            }
        } else {
            if ($isApiRequest) {
                http_response_code(404);
                $this->viewHelper->respondJson(['message' => 'Product not found']);
            } else {
                http_response_code(404);
                echo 'Product not found';
            }
        }
    }


    public function createProduct()
    {
        $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'price' => $_POST['price'] ?? '',
                'image_url' => $_POST['image_url'] ?? '',
                'quantity_available' => $_POST['quantity_available'] ?? ''
            ];

            $errors = [];
            if (empty($data['name'])) {
                $errors[] = 'Name is required.';
            }
            if (empty($data['price']) || !is_numeric($data['price'])) {
                $errors[] = 'Valid price is required.';
            }
            if (empty($data['quantity_available']) || !is_numeric($data['quantity_available'])) {
                $errors[] = 'Valid quantity is required.';
            }

            if (!empty($errors)) {
                if ($isApiRequest) {
                    // API response with validation errors
                    http_response_code(400);
                    $this->viewHelper->respondJson(['errors' => $errors]);
                } else {
                    // Web response with validation errors
                    $this->viewHelper->respondOrRender('products/create', ['errors' => $errors, 'data' => $data]);
                }
                return;
            }

            // Create the product
            $this->productModel->createProduct($data);

            if ($isApiRequest) {
                $this->viewHelper->respondJson(['message' => 'Product created successfully']);
            } else {
                header('Location: /vms/products');
                exit;
            }
        } else {
            if ($isApiRequest) {
                http_response_code(405);
                $this->viewHelper->respondJson(['message' => 'Method Not Allowed']);
            } else {
                $this->viewHelper->respondOrRender('products/create', []);
            }
        }
    }


    public function updateProduct($id)
    {
        $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'price' => $_POST['price'] ?? '',
                'image_url' => $_POST['image_url'] ?? '',
                'quantity_available' => $_POST['quantity_available'] ?? ''
            ];

            $errors = [];
            if (empty($data['name'])) {
                $errors[] = 'Name is required.';
            }
            if (empty($data['price']) || !is_numeric($data['price'])) {
                $errors[] = 'Valid price is required.';
            }
            if (empty($data['quantity_available']) || !is_numeric($data['quantity_available'])) {
                $errors[] = 'Valid quantity is required.';
            }

            if (!empty($errors)) {
                if ($isApiRequest) {
                    http_response_code(400);
                    $this->viewHelper->respondJson(['errors' => $errors]);
                } else {
                    $product = $this->productModel->getProductById($id);
                    $this->viewHelper->respondOrRender('products/edit', ['errors' => $errors, 'product' => $product]);
                }
                return;
            }

            $this->productModel->updateProduct($id, $data);

            if ($isApiRequest) {
                $this->viewHelper->respondJson(['message' => 'Product updated successfully']);
            } else {
                header('Location: /vms/products');
                exit;
            }
        } else {
            $product = $this->productModel->getProductById($id);
            if ($isApiRequest) {
                $this->viewHelper->respondJson(['product' => $product]);
            } else {
                $this->viewHelper->respondOrRender('products/edit', ['product' => $product]);
            }
        }
    }

    public function updateProductApi($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            return;
        }

        $inputData = file_get_contents("php://input");

        $data = json_decode($inputData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            return;
        }

        $errors = [];
        if (empty($data['name'])) {
            $errors[] = 'Name is required.';
        }
        if (empty($data['price']) || !is_numeric($data['price'])) {
            $errors[] = 'Valid price is required.';
        }
        if (empty($data['quantity_available']) || !is_numeric($data['quantity_available'])) {
            $errors[] = 'Valid quantity is required.';
        }

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            return;
        }

        try {
            $this->productModel->updateProduct($id, $data);
            http_response_code(200);
            echo json_encode(['message' => 'Product updated successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error', 'details' => $e->getMessage()]);
        }
    }

    public function deleteProduct($id)
    {
        $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;

        $this->productModel->deleteProduct($id);

        if ($isApiRequest) {
            $this->viewHelper->respondJson(['message' => 'Product deleted successfully']);
        } else {
            header('Location: /vms/products');
            exit;
        }
    }
}
