<?php
$title = 'Product List - Vending Machine System';
$header = 'Product List';
ob_start();
?>

<!-- Product List Content -->
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($header); ?></h1>
        <a href="/vms/products/create" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Add New Product</a>
    </div>

    <!-- Table for displaying products -->
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Available</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($products as $product): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="h-16 w-16 object-cover">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($product['quantity_available']); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="/vms/products/<?php echo htmlspecialchars($product['id']); ?>" class="text-blue-600 hover:text-blue-900">View</a>
                        <a href="/vms/products/edit/<?php echo htmlspecialchars($product['id']); ?>" class="text-indigo-600 hover:text-indigo-900 ml-4">Edit</a>
                        <form action="/vms/products/<?php echo htmlspecialchars($product['id']); ?>" method="post" class="inline-block ml-4" onsubmit="return confirmDelete('<?php echo htmlspecialchars($product['name']); ?>');">
                            <!-- Hidden field to simulate DELETE method -->
                            <input type="hidden" name="_method" value="DELETE">
                            
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        <?php if ($totalPages > 1): ?>
            <nav class="flex justify-between items-center">
                <ul class="inline-flex">
                    <?php if ($currentPage > 1): ?>
                        <li>
                            <a href="?page=<?php echo $currentPage - 1; ?>" class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                            <a href="?page=<?php echo $i; ?>" class="px-3 py-2 text-sm font-medium <?php echo ($i == $currentPage) ? 'bg-blue-500 text-white' : 'text-gray-500 hover:text-gray-700'; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                        <li>
                            <a href="?page=<?php echo $currentPage + 1; ?>" class="px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
