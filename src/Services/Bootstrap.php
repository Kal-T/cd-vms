<?php

use App\Controllers\HomeController;
use App\Services\Container;
use App\Services\Database;
use App\Models\Product;
use App\Models\Order;
use App\Controllers\ProductsController;
use App\Controllers\OrdersController;
use App\Controllers\TransactionsController;
use App\Controllers\UsersController;
use App\Models\Transaction;
use App\Models\User;

// Create the DI container
$container = new Container();

$dsn = $_ENV['DB_TYPE'] . ':host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8';
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

// Register services with lazy loading
$container->set('database', function() use ($dsn, $user, $password) {
    return new Database($dsn, $user, $password);
});

$container->set('productModel', function() use ($container) {
    return new Product($container->get('database'));
});

$container->set('userModel', function() use ($container) {
    return new User($container->get('database'));
});

$container->set('orderModel', function() use ($container) {
    return new Order($container->get('database'));
});

$container->set('transactionModel', function() use ($container) {
    return new Transaction($container->get('database'));
});

$container->set('App\Controllers\ProductsController', function() use ($container) {
    return new ProductsController($container->get('productModel'));
});

$container->set('App\Controllers\UsersController', function() use ($container) {
    return new UsersController($container->get('userModel'));
});

$container->set('App\Controllers\OrdersController', function() use ($container) {
    return new OrdersController($container->get('orderModel'), $container->get('productModel'), $container->get('transactionModel'));
});

$container->set('App\Controllers\TransactionsController', function() use ($container) {
    return new TransactionsController($container->get('transactionModel'));
});

$container->set('App\Controllers\HomeController', function() {
    return new HomeController();
});

return $container;
