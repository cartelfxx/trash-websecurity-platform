<?php
$page_title = "Blocked IPs";
ob_start();
?>
<h2>Blocked IPs</h2>
<form class="row g-3 mb-4" method="post" action="/block_ip">
    <div class="col-auto">
        <input type="text" name="ip" class="form-control" placeholder="IP Address" required>
    </div>
    <div class="col-auto">
        <input type="text" name="reason" class="form-control" placeholder="Reason">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-ban"></i> Block IP</button>
    </div>
</form>
<table id="blockedTable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>IP Address</th>
            <th>Reason</th>
            <th>Blocked At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($blocked as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['ip_address']) ?></td>
            <td><?= htmlspecialchars($row['reason']) ?></td>
            <td><?= $row['blocked_at'] ?></td>
            <td>
                <form method="post" action="/unblock_ip" style="display:inline;">
                    <input type="hidden" name="ip" value="<?= htmlspecialchars($row['ip_address']) ?>">
                    <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-unlock"></i> Unblock</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
$(function() {
    $('#blockedTable').DataTable();
});
</script>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
