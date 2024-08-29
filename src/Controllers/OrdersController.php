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

    public function __construct(Order $orderModel, Product $productModel, Transaction $transactionModel)
    {
        $this->orderModel = $orderModel;
        $this->productModel = $productModel;
        $this->transactionModel = $transactionModel;
    }

    public function createOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            $product = $this->productModel->getProductById($productId);

            if ($product && $product['quantity_available'] >= $quantity) {
                $orderId = $this->orderModel->createOrder([
                    'user_id' => 1, // Replace with actual user ID
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

                header('Location: /vms/orders');
                exit;
            } else {
                echo 'Product not available or insufficient quantity';
            }
        } else {
            $products = $this->productModel->getAllProducts();
            ViewHelper::respondOrRender('orders/create', ['products' => $products]);
        }
    }

    public function viewOrder($id)
    {
        $order = $this->orderModel->getOrderById($id);
        if ($order) {
            ViewHelper::respondOrRender('orders/view', ['order' => $order]);
        } else {
            http_response_code(404);
            echo 'Order not found';
        }
    }

    public function listOrders()
    {
        $orders = $this->orderModel->getAllOrders();
        ViewHelper::respondOrRender('orders/list', ['orders' => $orders]);
    }
}
