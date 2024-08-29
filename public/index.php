<?php

require '../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Create the DI container
$container = require '../src/Services/Bootstrap.php';

// Define routes
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $routes = include __DIR__ . '/../src/Routes/web.php';
    $routes($r);
});

// Dispatch the request
$httpMethod = $_SERVER['REQUEST_METHOD'];

// Check for _method in POST or PUT/DELETE requests
if ($httpMethod === 'POST' && isset($_POST['_method'])) {
    $httpMethod = strtoupper($_POST['_method']);
}

$uri = $_SERVER['REQUEST_URI'];
$uri = parse_url($uri, PHP_URL_PATH);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        [$controllerName, $method] = $routeInfo[1];
        $controller = $container->get($controllerName);
        $response = $controller->$method(...$routeInfo[2]);
        break;
}
