<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/models/IpBlocker.php';
require_once __DIR__ . '/models/RateLimiter.php';

$ipBlocker = new IpBlocker($db);
$rateLimiter = new RateLimiter($db);
$current_ip = $_SERVER['REMOTE_ADDR'];


if ($_POST) {
    if (isset($_POST['block_me'])) {
        $ipBlocker->block($current_ip, 'Test bloklama - Manuel');
        echo "<div style='background: #d4edda; color: #155724; padding: 10px; margin: 10px; border-radius: 5px;'>IP adresiniz bloklandı!</div>";
    } elseif (isset($_POST['unblock_me'])) {
        $ipBlocker->unblock($current_ip);
        echo "<div style='background: #d1ecf1; color: #0c5460; padding: 10px; margin: 10px; border-radius: 5px;'>IP adresiniz bloktan kaldırıldı!</div>";
    } elseif (isset($_POST['reset_rate_limit'])) {
        $rateLimiter->resetRateLimit($current_ip);
        echo "<div style='background: #d1ecf1; color: #0c5460; padding: 10px; margin: 10px; border-radius: 5px;'>Rate limit sıfırlandı!</div>";
    }
}


$is_blocked = $ipBlocker->isBlocked($current_ip);
$blocked_ips = $ipBlocker->getBlocked();
$rate_limit_info = $rateLimiter->getRateLimitInfo($current_ip);
?>

<!DOCTYPE html>
<html>
<head>
    <title>IP Bloklama Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .card { border: 1px solid #ddd; padding: 20px; margin: 10px 0; border-radius: 5px; }
        .blocked { background: #f8d7da; border-color: #f5c6cb; }
        .not-blocked { background: #d4edda; border-color: #c3e6cb; }
        .btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 3px; cursor: pointer; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-info { background: #17a2b8; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .progress { width: 100%; height: 20px; background: #e9ecef; border-radius: 10px; overflow: hidden; }
        .progress-bar { height: 100%; background: #007bff; transition: width 0.3s; }
    </style>
</head>
<body>
    <h1>IP Bloklama Test Sayfası</h1>
    
    <div class="card <?= $is_blocked ? 'blocked' : 'not-blocked' ?>">
        <h3>Mevcut Durum</h3>
        <p><strong>IP Adresiniz:</strong> <?= htmlspecialchars($current_ip) ?></p>
        <p><strong>Durum:</strong> 
            <?php if ($is_blocked): ?>
                <span style="color: red;">❌ BLOKLANDI</span>
            <?php else: ?>
                <span style="color: green;">✅ BLOKLANMADI</span>
            <?php endif; ?>
        </p>
    </div>

    <div class="card">
        <h3>Rate Limiting Durumu</h3>
        <?php if ($rate_limit_info): ?>
            <p><strong>Son 1 dakikada istek sayısı:</strong> <?= $rate_limit_info['request_count'] ?>/10</p>
            <div class="progress">
                <div class="progress-bar" style="width: <?= min(100, ($rate_limit_info['request_count'] / 10) * 100) ?>%"></div>
            </div>
            <p><strong>Son istek:</strong> <?= $rate_limit_info['last_request'] ?></p>
            <?php if ($rate_limit_info['request_count'] >= 8): ?>
                <p style="color: orange;">⚠️ Dikkat: Rate limit'e yaklaşıyorsunuz!</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Henüz rate limiting kaydı yok.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Test İşlemleri</h3>
        <form method="post">
            <button type="submit" name="block_me" class="btn btn-danger">Kendimi Blokla</button>
            <button type="submit" name="unblock_me" class="btn btn-success">Bloktan Kaldır</button>
            <button type="submit" name="reset_rate_limit" class="btn btn-warning">Rate Limit Sıfırla</button>
        </form>
    </div>

    <div class="card">
        <h3>Bloklanan IP'ler</h3>
        <?php if (empty($blocked_ips)): ?>
            <p>Henüz bloklanan IP yok.</p>
        <?php else: ?>
            <table border="1" style="width: 100%; border-collapse: collapse;">
                <tr>
                    <th>IP Adresi</th>
                    <th>Sebep</th>
                    <th>Bloklanma Tarihi</th>
                </tr>
                <?php foreach ($blocked_ips as $ip): ?>
                <tr>
                    <td><?= htmlspecialchars($ip['ip_address']) ?></td>
                    <td><?= htmlspecialchars($ip['reason']) ?></td>
                    <td><?= htmlspecialchars($ip['blocked_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Test Senaryoları</h3>
        <p><strong>Manuel bloklama:</strong></p>
        <ol>
            <li>"Kendimi Blokla" butonuna tıklayın</li>
            <li>Ana sayfaya gitmeye çalışın - 403 hatası almalısınız</li>
            <li>Bu sayfaya geri dönün ve "Bloktan Kaldır" ile test edin</li>
        </ol>
        
        <p><strong>Otomatik bloklama (Rate Limiting):</strong></p>
        <ol>
            <li>Bu sayfayı çok hızlı yenileyin (F5 tuşuna hızlıca basın)</li>
            <li>1 dakika içinde 10'dan fazla istek yapın</li>
            <li>Otomatik olarak bloklanacaksınız</li>
            <li>"Rate Limit Sıfırla" ile test edebilirsiniz</li>
        </ol>
    </div>

    <div class="card">
        <a href="/dashboard" class="btn btn-info">Dashboard'a Git</a>
        <a href="/security" class="btn btn-info">Güvenlik Sayfası</a>
    </div>

    <script>

        function autoRefresh() {
            location.reload();
        }
        


    </script>
</body>
</html> 