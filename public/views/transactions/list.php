<?php
$title = 'Transaction List - Vending Machine System';
$header = 'Transaction List';
ob_start();
?>

<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold"><?php echo htmlspecialchars($header); ?></h1>
    </div>

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= htmlspecialchars($transaction['id']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($transaction['order_id']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500">$<?= htmlspecialchars($transaction['amount']) ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($transaction['transaction_date']) ?></td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <a href="/vms/transactions/<?= htmlspecialchars($transaction['id']) ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
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
