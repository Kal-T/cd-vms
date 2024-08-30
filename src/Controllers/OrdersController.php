<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Helpers\ViewHelper;
use App\Models\Transaction;

class OrdersController
{
    private $orderModel;
    private $productModel;
    private $transactionModel;
    private $viewHelper;

    public function __construct(Order $orderModel, Product $productModel, Transaction $transactionModel, ViewHelper $viewHelper)
    {
        $this->orderModel = $orderModel;
        $this->productModel = $productModel;
        $this->transactionModel = $transactionModel;
        $this->viewHelper = $viewHelper;
    }

    public function createOrder()
    {
        $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'] ?? '';
            $quantity = $_POST['quantity'] ?? '';
    
            $product = $this->productModel->getProductById($productId);
    
            if ($product && $product['quantity_available'] >= $quantity) {
                $orderId = $this->orderModel->createOrder([
                    'user_id' => $_SESSION['user_id'], // Replace with actual user ID
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'total_amount' => $product['price'] * $quantity,
                    'status' => 'pending'
                ]);
    
                $this->productModel->updateProductQuantity($productId, [
                    'quantity_available' => $product['quantity_available'] - $quantity
                ]);
    
                // Create a transaction
                $this->transactionModel->createTransaction([
                    'order_id' => $orderId,
                    'amount' => $product['price'] * $quantity,
                    'payment_method' => 'credit_card', // Replace with actual payment method
                    'status' => 'successful'
                ]);
    
                if ($isApiRequest) {
                    // API response
                    $this->viewHelper->respondJson(['message' => 'Order created successfully', 'order_id' => $orderId]);
                } else {
                    // Web response
                    header('Location: /vms/orders');
                    exit;
                }
            } else {
                if ($isApiRequest) {
                    // API response
                    http_response_code(400);
                    $this->viewHelper->respondJson(['message' => 'Product not available or insufficient quantity']);
                } else {
                    // Web response
                    echo 'Product not available or insufficient quantity';
                }
            }
        } else {
            if ($isApiRequest) {
                // API response for GET requests (optional, if needed)
                http_response_code(405); // Method Not Allowed
                $this->viewHelper->respondJson(['message' => 'Method Not Allowed']);
            } else {
                // Web response
                $products = $this->productModel->getAllProducts();
                $this->viewHelper->respondOrRender('orders/create', ['products' => $products]);
            }
        }
    }
    

    public function viewOrder($id)
    {
        $order = $this->orderModel->getOrderById($id);
        $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;

        if ($order) {
            if ($isApiRequest) {
                // API response
                $this->viewHelper->respondJson(['order' => $order]);
            } else {
                // Web response
                $this->viewHelper->respondOrRender('orders/view', ['order' => $order]);
            }
        } else {
            if ($isApiRequest) {
                // API response
                http_response_code(404);
                $this->viewHelper->respondJson(['message' => 'Order not found']);
            } else {
                // Web response
                http_response_code(404);
                echo 'Order not found';
            }
        }
    }
    public function listOrders()
    {
        $orders = $this->orderModel->getAllOrders();
        $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;

        if ($isApiRequest) {
            // API response
            $this->viewHelper->respondJson(['orders' => $orders]);
        } else {
            // Web response
            $this->viewHelper->respondOrRender('orders/list', ['orders' => $orders]);
        }
    }
}
