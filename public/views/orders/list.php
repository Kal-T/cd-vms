<?php
$title = 'Order List - Vending Machine System';
$header = 'Order List';
ob_start();
?>

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($header); ?></h1>
    </div>

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($order['id']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($order['product_id']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($order['quantity']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($order['order_date']) ?></td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <a href="/vms/orders/<?= htmlspecialchars($order['id']) ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination (if needed) -->
    <!-- Pagination logic goes here -->

</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
