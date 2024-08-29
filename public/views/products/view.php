<?php
$title = 'Product Details - Vending Machine System';
$header = 'Product Details';
ob_start();
?>

<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($header); ?></h1>
    <div class="bg-white shadow-md rounded-md p-6">
        <div class="flex items-center">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="h-48 w-48 object-cover rounded-md">
            <div class="ml-6">
                <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="text-lg text-gray-700">Price: $<?php echo number_format($product['price'], 2); ?></p>
                <p class="text-lg text-gray-700">Quantity Available: <?php echo htmlspecialchars($product['quantity_available']); ?></p>
                
                <!-- Order Form -->
                <form action="/vms/orders" method="post" class="mt-4">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="<?php echo htmlspecialchars($product['quantity_available']); ?>" value="1" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Order Now</button>
                </form>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <a href="/vms/products" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Back to List</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
