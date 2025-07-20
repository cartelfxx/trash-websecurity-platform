<?php
$page_title = "Users";
ob_start();
?>
<h2>Users</h2>
<p>User management panel (not implemented in this demo).</p>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
