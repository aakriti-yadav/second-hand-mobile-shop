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
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="app-shell">
        <?php include 'includes/navbar.php'; ?>
        <div class="main-content">
            <div class ="page-header">
                <h2>Inventory</h2>
                <a href="add_device.php" class="btn btn-primary">+ Add Device</a>
            </div>
                    
            <form method="GET" class="d-flex gap-2 mb-4" style="max-width: 485px;">
                    <input type="text" name="search" class="form-control" placeholder="Search by brand, model, or IMEI/serial" value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-outline-secondary" style="border-radius: 0;">Search</button>
                    <a href="inventory.php" class="btn btn-outline-secondary" style="border-radius: 0 6px 6px 0;">Clear</a>
            </form>
            
            <div class ="table-card">
                <table class="table mb-0">
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
                            <td colspan="11" class="text-center py-5 text-muted">No devices found matching your search.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($devices as $device): ?>
                        <tr>
                            <td><?= htmlspecialchars($device['brand']) ?></td>
                            <td>
                                <?php if ($device['image_filename']): ?>
                                    <img src="assets/uploads/<?= htmlspecialchars($device['image_filename']) ?>" class="device-thumb">
                                <?php else: ?>
                                        <div class="device-thumb-empty"></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($device['model']) ?></td>
                                <td><?= htmlspecialchars($device['device_type']) ?></td>
                                <td class="font-monospace small"><?= htmlspecialchars($device['imei_serial']) ?></td>
                                <td><?= htmlspecialchars($device['storage']) ?></td>
                                <td><?= htmlspecialchars($device['battery_health'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($device['purchase_price']) ?></td>
                                <td><?= htmlspecialchars($device['asking_price']) ?></td>
                                <td><span class="status-pill status-<?= strtolower($device['status']) ?>"><?= htmlspecialchars($device['status']) ?></span></td>
                                <td>
                                    <a href="edit_device.php?id=<?= $device['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>