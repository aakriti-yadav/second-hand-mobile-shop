<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM devices WHERE status = 'Available'");
$in_stock = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) AS total FROM devices WHERE status = 'Sold'");
$total_sold = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT SUM(selling_price) AS total FROM sales");
$total_revenue = $stmt->fetch()['total'] ?? 0;

$stmt = $pdo->query("SELECT SUM(sales.selling_price - devices.purchase_price) AS total 
                      FROM sales 
                      JOIN devices ON sales.device_id = devices.id");
$total_profit = $stmt->fetch()['total'] ?? 0;

$stmt = $pdo->query("SELECT * FROM devices ORDER BY created_at DESC LIMIT 5");
$recent_devices = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Mobile Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    <?php include 'includes/navbar.php'; ?>
    <div class="main-content">
        <div class="page-header">
            <h2>Dashboard</h2>
            <span style="color: var(--color-ink-muted); font-size: 14px;">Welcome back, <?= htmlspecialchars($_SESSION['username']) ?></span>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card accent-success">
                    <div class="stat-label">In Stock</div>
                    <div class="stat-value"><?= $in_stock ?></div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card accent-warning">
                    <div class="stat-label">Devices Sold</div>
                    <div class="stat-value"><?= $total_sold ?></div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card accent-primary">
                    <div class="stat-label">Revenue</div>
                    <div class="stat-value"><?= number_format($total_revenue, 0) ?></div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card accent-primary">
                    <div class="stat-label">Profit</div>
                    <div class="stat-value"><?= number_format($total_profit, 0) ?></div>
                </div>
            </div>
        </div>

        <div class="page-header">
            <h2 style="font-size: 16px;">Recently Added</h2>
            <a href="inventory.php" class="btn btn-sm btn-outline-secondary">View All</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Asking Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_devices as $d): ?>
                <tr>
                    <td style="width: 56px;">
                        <?php if ($d['image_filename']): ?>
                            <img src="assets/uploads/<?= htmlspecialchars($d['image_filename']) ?>" width="44" height="44" style="object-fit: cover; border-radius: 6px;">
                        <?php else: ?>
                            <div style="width: 44px; height: 44px; background: var(--color-neutral-bg); border-radius: 6px;"></div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($d['brand']) ?></td>
                    <td><?= htmlspecialchars($d['model']) ?></td>
                    <td><?= htmlspecialchars($d['device_type']) ?></td>
                    <td><span class="status-pill status-<?= strtolower($d['status']) ?>"><?= htmlspecialchars($d['status']) ?></span></td>
                    <td><?= number_format($d['asking_price'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>