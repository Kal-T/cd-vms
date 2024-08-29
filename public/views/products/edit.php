<?php
$title = 'Edit Product - Vending Machine System';
$header = 'Edit Product';
ob_start();
?>

<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6"><?php echo htmlspecialchars($header); ?></h1>
    
    <div class="bg-white shadow-md rounded-md p-6">
    <form action="/vms/products/<?php echo htmlspecialchars($product['id']); ?>" method="post" class="space-y-4">
            <!-- Hidden field to simulate PUT method -->
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
            </div>
            
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
            </div>
            
            <div class="mb-4">
                <label for="quantity_available" class="block text-sm font-medium text-gray-700">Quantity Available:</label>
                <input type="number" id="quantity_available" name="quantity_available" value="<?php echo htmlspecialchars($product['quantity_available']); ?>" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
            </div>
            
            <div class="mb-4">
                <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL:</label>
                <input type="text" id="image_url" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2">
            </div>
            
            <div class="flex justify-between">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Save Changes</button>
                <a href="/vms/products" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Back to List</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
