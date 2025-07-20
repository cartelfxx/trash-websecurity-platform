<?php
$page_title = "Dashboard";
ob_start();
?>
<h2>Welcome to WebGuard</h2>
<p class="lead">Your website's security dashboard.</p>
<div class="row my-4">
    <div class="col-md-4">
        <div class="card text-bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-users"></i> Total Visitors</h5>
                <p class="card-text fs-2"><?= $stats['total'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-ban"></i> Blocked IPs</h5>
                <p class="card-text fs-2"><?= $stats['blocked'] ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-calendar-day"></i> Today's Access</h5>
                <p class="card-text fs-2"><?= $stats['today'] ?></p>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
