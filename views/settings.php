<?php
$page_title = "Settings";
ob_start();
?>
<h2>Settings</h2>
<p>Settings panel (not implemented in this demo).</p>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
