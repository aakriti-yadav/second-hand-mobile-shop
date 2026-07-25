<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';

$stmt = $pdo->query("SELECT * FROM devices ORDER BY created_at DESC");
$devices = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory - Mobile Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Inventory</h2>
    <a href="add_device.php" class="btn btn-primary mb-3">+ Add Device</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Brand</th>
                <th>Model</th>
                <th>Type</th>
                <th>IMEI/Serial</th>
                <th>Storage</th>
                <th>Battery Health</th>
                <th>Purchase Price</th>
                <th>Asking Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($devices as $device): ?>
            <tr>
                <td><?= htmlspecialchars($device['brand']) ?></td>
                <td><?= htmlspecialchars($device['model']) ?></td>
                <td><?= htmlspecialchars($device['device_type']) ?></td>
                <td><?= htmlspecialchars($device['imei_serial']) ?></td>
                <td><?= htmlspecialchars($device['storage']) ?></td>
                <td><?= htmlspecialchars($device['battery_health'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($device['purchase_price']) ?></td>
                <td><?= htmlspecialchars($device['asking_price']) ?></td>
                <td><?= htmlspecialchars($device['status']) ?></td>
                <td>
                    <a href="edit_device.php?id=<?= $device['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>