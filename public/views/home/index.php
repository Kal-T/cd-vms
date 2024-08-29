<?php
$title = 'Home - Vending Machine System';
$header = 'Home';
ob_start();
?>

<!-- Home Page Content -->
<p>Welcome to the Vending Machine System. Explore our products and place your orders.</p>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
