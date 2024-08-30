<?php

namespace App\Tests\Controllers;

use App\Controllers\ProductsController;
use App\Helpers\Response;
use App\Models\Product;
use App\Helpers\ViewHelper;
use PHPUnit\Framework\TestCase;

class ProductsControllerTest extends TestCase
{
    private $productModel;
    private $viewHelper;
    private $controller;
    private $response;

    protected function setUp(): void
    {
        $this->productModel = $this->createMock(Product::class);
        $this->viewHelper = $this->createMock(ViewHelper::class);
        $this->response = $this->createMock(Response::class);
        $this->controller = new ProductsController($this->productModel, $this->viewHelper, $this->response);
    }

    public function testListProductsWeb()
    {
        $_SERVER['REQUEST_URI'] = '/vms/products';
        $_GET['page'] = 1;

        $this->productModel->expects($this->once())
            ->method('getProducts')
            ->with(10, 0)
            ->willReturn([]);

        $this->productModel->expects($this->once())
            ->method('getTotalProducts')
            ->willReturn(0);

        $this->viewHelper->expects($this->once())
            ->method('respondOrRender')
            ->with('products/list', [
                'products' => [],
                'currentPage' => 1,
                'totalPages' => 0
            ]);

        $this->controller->listProducts();
    }

    public function testListProductsApi()
    {
        $_SERVER['REQUEST_URI'] = '/vms/api/products';
        $_GET['page'] = 1;

        $this->productModel->expects($this->once())
            ->method('getProducts')
            ->with(10, 0)
            ->willReturn([]);

        $this->productModel->expects($this->once())
            ->method('getTotalProducts')
            ->willReturn(0);

        $this->viewHelper->expects($this->once())
            ->method('respondJson')
            ->with([
                'products' => [],
                'currentPage' => 1,
                'totalPages' => 0
            ]);

        $this->controller->listProducts();
    }

    public function testViewProductFound()
    {
        $productId = 1;
        $product = ['id' => $productId, 'name' => 'Sample Product'];

        $_SERVER['REQUEST_URI'] = '/vms/products/1';

        $this->productModel->expects($this->once())
            ->method('getProductById')
            ->with($productId)
            ->willReturn($product);

        $this->viewHelper->expects($this->once())
            ->method('respondOrRender')
            ->with('products/view', ['product' => $product]);

        $this->controller->viewProduct($productId);
    }

    public function testViewProductNotFound()
    {
        $productId = 999;

        $_SERVER['REQUEST_URI'] = '/vms/products/999';

        $this->productModel->expects($this->once())
            ->method('getProductById')
            ->with($productId)
            ->willReturn(null);

        $this->viewHelper->expects($this->once())
            ->method('respondJson')
            ->with(['message' => 'Product not found']);

        $this->controller->viewProduct($productId);
    }

    public function testCreateProductSuccess()
    {
        $_SERVER['REQUEST_URI'] = '/vms/products';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => 'New Product',
            'price' => 100,
            'image_url' => 'http://example.com/image.jpg',
            'quantity_available' => 10
        ];

        $this->productModel->expects($this->once())
            ->method('createProduct')
            ->with($_POST);

        $this->viewHelper->expects($this->once())
            ->method('respondJson')
            ->with(['message' => 'Product created successfully']);

        $this->controller->createProduct();
    }

    public function testCreateProductValidationErrors()
{
    $_SERVER['REQUEST_URI'] = '/vms/products/create';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    $_POST = [
        'name' => '',
        'price' => 'invalid',
        'quantity_available' => 'invalid',
        'image_url' => ''
    ];

    // Mock Product model to not actually interact with the database
    $this->productModel->expects($this->never())
        ->method('createProduct');

    $this->viewHelper->expects($this->once())
        ->method('respondOrRender')
        ->with('products/create', [
            'errors' => [
                'Name is required.',
                'Valid price is required.',
                'Valid quantity is required.'
            ],
            'data' => [
                'name' => '',
                'price' => 'invalid',
                'quantity_available' => 'invalid',
                'image_url' => ''
            ]
        ]);

    $this->controller->createProduct();
}


    public function testUpdateProductSuccess()
    {
        $productId = 1;
        $_SERVER['REQUEST_URI'] = '/vms/products';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => 'Updated Product',
            'price' => 150,
            'image_url' => 'http://example.com/image_updated.jpg',
            'quantity_available' => 15
        ];

        $this->productModel->expects($this->once())
            ->method('updateProduct')
            ->with($productId, $_POST);

        $this->viewHelper->expects($this->once())
            ->method('respondJson')
            ->with(['message' => 'Product updated successfully']);

        $this->controller->updateProduct($productId);
    }

    public function testUpdateProductValidationErrors()
    {
        $productId = 1;
        $_SERVER['REQUEST_URI'] = '/vms/products';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => '',
            'price' => 'invalid',
            'quantity_available' => 'invalid'
        ];

        $this->viewHelper->expects($this->once())
            ->method('respondOrRender')
            ->with('products/edit', [
                'errors' => [
                    'Name is required.',
                    'Valid price is required.',
                    'Valid quantity is required.'
                ],
                'product' => $this->productModel->getProductById($productId)
            ]);

        $this->controller->updateProduct($productId);
    }

    public function testUpdateProductApi()
    {
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['REQUEST_URI'] = '/vms/api/products/1';
        $inputData = json_encode([
            'name' => 'Product Name',
            'price' => 100,
            'quantity_available' => 10
        ]);
        file_put_contents('php://input', $inputData);

        $this->productModel->expects($this->once())
            ->method('updateProduct')
            ->with(1, [
                'name' => 'Product Name',
                'price' => 100,
                'quantity_available' => 10
            ]);

        $this->response->expects($this->once())
            ->method('setStatusCode')
            ->with(200);

        $this->response->expects($this->once())
            ->method('sendJson')
            ->with(['message' => 'Product updated successfully']);

        $this->controller->updateProductApi(1);
    }
    public function testDeleteProductApiRequest()
{
    $productId = 1;
    $_SERVER['REQUEST_URI'] = '/vms/api/products'; // Set request URI to simulate an API request
    $_SERVER['REQUEST_METHOD'] = 'POST'; // Simulate a POST request

    // Mock the Product model's deleteProduct method
    $this->productModel->expects($this->once())
        ->method('deleteProduct')
        ->with($productId);

    // Mock the ViewHelper to ensure respondJson is called with the correct parameters
    $this->viewHelper->expects($this->once())
        ->method('respondJson')
        ->with(['message' => 'Product deleted successfully']);

    // Create the ProductsController with the mocked dependencies
    $this->controller = new ProductsController($this->productModel, $this->viewHelper);

    // Call the method under test
    $this->controller->deleteProduct($productId);
}

}

