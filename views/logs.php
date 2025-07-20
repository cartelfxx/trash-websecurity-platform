<?php
$page_title = "IP Logs";
ob_start();
?>
<h2>IP Logs</h2>
<table id="logsTable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>IP Address</th>
            <th>User Agent</th>
            <th>Request URI</th>
            <th>Date & Time</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['ip_address']) ?></td>
            <td><?= htmlspecialchars($row['user_agent']) ?></td>
            <td><?= htmlspecialchars($row['uri']) ?></td>
            <td><?= $row['datetime'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
$(function() {
    $('#logsTable').DataTable();
});
</script>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
