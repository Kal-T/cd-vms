<?php
$title = 'Order Details - Vending Machine System';
$header = 'Order Details';
ob_start();
?>

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($header); ?></h1>
        <a href="/vms/orders" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Back to List</a>
    </div>

    <!-- Order Details -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Order #<?= htmlspecialchars($order['id']) ?></h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Product ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><?= htmlspecialchars($order['product_id']) ?></dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Quantity</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><?= htmlspecialchars($order['quantity']) ?></dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><?= htmlspecialchars($order['order_date']) ?></dd>
                </div>
                <?php if ($order['transaction_id']): ?>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Transaction ID</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><?= htmlspecialchars($order['transaction_id']) ?></dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">$<?= htmlspecialchars($order['amount']) ?></dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Transaction Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><?= htmlspecialchars($order['transaction_date']) ?></dd>
                    </div>
                <?php endif; ?>
            </dl>
        </div>
    </div>

    <!-- New Order Form -->
    <!-- <div class="mt-6">
        <h2 class="text-xl font-semibold mb-4">Create New Order</h2>
        <form action="/vms/orders/create" method="post" class="bg-white shadow sm:rounded-lg p-6">
            <div class="grid grid-cols-1 gap-y-6">
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Product ID</label>
                    <input type="text" id="product_id" name="product_id" required class="mt-1 block w-full shadow-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" id="quantity" name="quantity" required min="1" class="mt-1 block w-full shadow-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="flex items-center">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Create Order</button>
                </div>
            </div>
        </form>
    </div> -->
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
