<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM devices WHERE brand LIKE ? OR model LIKE ? OR imei_serial LIKE ? ORDER BY created_at DESC");
    $searchTerm = '%' . $search . '%';
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
} else {
    $stmt = $pdo->query("SELECT * FROM devices ORDER BY created_at DESC");
}
$devices = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory - Mobile Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
<div class="container mt-5">
    <h2 class="mb-4">Inventory</h2>
    <a href="add_device.php" class="btn btn-primary mb-3">+ Add Device</a>
    <form method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by brand, model, or IMEI/serial" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-outline-primary">Search</button>
            <a href="inventory.php" class="btn btn-outline-secondary">Clear</a>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Brand</th>
                <th>Image</th>
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
            <?php if (empty($devices)): ?>
            <tr>
                <td colspan="10" class="text-center text-muted">No devices found matching your search.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($devices as $device): ?>
            <tr>
                <td><?= htmlspecialchars($device['brand']) ?></td>
                <td>
                    <?php if ($device['image_filename']): ?>
                        <img src="assets/uploads/<?= htmlspecialchars($device['image_filename']) ?>" width="60" height="60" style="object-fit: cover;">
                    <?php else: ?>
                        No image
                    <?php endif; ?>
                </td>
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
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>