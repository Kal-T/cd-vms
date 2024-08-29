<?php
$title = 'Transaction Details - Vending Machine System';
$header = 'Transaction Details';
ob_start();
?>

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($header); ?></h1>
        <a href="/vms/transactions" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Back to List</a>
    </div>

    <!-- Transaction Details -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Transaction #<?= htmlspecialchars($transaction['id']) ?></h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Order ID</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><?= htmlspecialchars($transaction['order_id']) ?></dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Amount</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">$<?= htmlspecialchars($transaction['amount']) ?></dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Transaction Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0"><?= htmlspecialchars($transaction['transaction_date']) ?></dd>
                </div>
            </dl>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
