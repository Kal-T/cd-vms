<?php

use App\Controllers\HomeController;
use App\Controllers\OrdersController;
use App\Controllers\ProductsController;
use App\Controllers\TransactionsController;
use App\Controllers\UsersController;

// Define UI routes
return function (FastRoute\RouteCollector $r) {
    // UI Routes
    $r->addGroup('/vms', function (FastRoute\RouteCollector $r) {
        // Home
        $r->addRoute('GET', '/', [HomeController::class, 'index']);

        // Product routes
        $r->addRoute('GET', '/products', [ProductsController::class, 'listProducts']);
        $r->addRoute('GET', '/products/{id:\d+}', [ProductsController::class, 'viewProduct']);
        $r->addRoute('GET', '/products/create', [ProductsController::class, 'createProduct']);
        $r->addRoute('POST', '/products', [ProductsController::class, 'createProduct']);
        $r->addRoute('GET', '/products/edit/{id:\d+}', [ProductsController::class, 'updateProduct']);
        $r->addRoute('PUT', '/products/{id:\d+}', [ProductsController::class, 'updateProduct']);
        $r->addRoute('DELETE', '/products/{id:\d+}', [ProductsController::class, 'deleteProduct']);

        // User routes
        $r->addRoute('GET', '/login', [UsersController::class, 'login']);
        $r->addRoute('POST', '/login', [UsersController::class, 'login']);
        $r->addRoute('GET', '/register', [UsersController::class, 'register']);
        $r->addRoute('POST', '/register', [UsersController::class, 'register']);
        $r->addRoute('GET', '/logout', [UsersController::class, 'logout']);

        // Order routes
        $r->addRoute('GET', '/orders', [OrdersController::class, 'listOrders']);
        $r->addRoute('GET', '/orders/{id:\d+}', [OrdersController::class, 'viewOrder']);
        $r->addRoute('POST', '/orders', [OrdersController::class, 'createOrder']);

        // Transaction routes
        $r->addRoute('GET', '/transactions', [TransactionsController::class, 'listTransactions']);
        $r->addRoute('GET', '/transactions/{id:\d+}', [TransactionsController::class, 'viewTransaction']);
    });

    $r->addGroup('/vms/api', function (FastRoute\RouteCollector $r) {
        $r->addRoute('POST', '/login', [UsersController::class, 'login']);
        $r->addRoute('POST', '/register', [UsersController::class, 'register']);
        $r->addRoute('POST', '/logout', [UsersController::class, 'logout']);
        $r->addRoute('GET', '/products', [ProductsController::class, 'listProducts']);
        $r->addRoute('GET', '/products/{id:\d+}', [ProductsController::class, 'viewProduct']);
        $r->addRoute('PUT', '/products/{id:\d+}', [ProductsController::class, 'updateProductApi']);
        $r->addRoute('DELETE', '/products/{id:\d+}', [ProductsController::class, 'deleteProduct']);
        $r->addRoute('POST', '/products', [ProductsController::class, 'createProduct']);
        $r->addRoute('GET', '/orders', [OrdersController::class, 'listOrders']);
        $r->addRoute('GET', '/orders/{id:\d+}', [OrdersController::class, 'viewOrder']);
        $r->addRoute('POST', '/orders', [OrdersController::class, 'createOrder']);
        $r->addRoute('GET', '/transactions', [TransactionsController::class, 'listTransactions']);
        $r->addRoute('GET', '/transactions/{id:\d+}', [TransactionsController::class, 'viewTransaction']);
    });
};
