<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM devices WHERE id= ?");
$stmt->execute([$id]);
$device = $stmt->fetch();

if (!$device) {
    die("Device not found.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $device_type = $_POST['device_type'];
    $imei_serial = $_POST['imei_serial'];
    $storage = $_POST['storage'];
    $battery_health = $_POST['battery_health'] !== '' ? $_POST['battery_health'] : null;
    $condition_notes = $_POST['condition_notes'];
    $purchase_price = $_POST['purchase_price'];
    $asking_price = $_POST['asking_price'];
    $status = $_POST['status'];
    $purchase_date = $_POST['purchase_date'];

    $errors = [];

    if (empty(trim($brand))) {
        $errors[] = "Brand is required.";
    }
    if (empty(trim($model))) {
        $errors[] = "Model is required.";
    }
    if ($purchase_price <= 0) {
        $errors[] = "Purchase price must be greater than 0.";
    }
    if ($asking_price <= 0) {
        $errors[] = "Asking price must be greater than 0.";
    }
    if ($battery_health !== null && ($battery_health < 0 || $battery_health > 100)) {
        $errors[] = "Battery health must be between 0 and 100.";
    }

    if (empty($errors)) { 
    $stmt = $pdo->prepare("UPDATE devices SET brand=?, model=?, device_type=?, imei_serial=?, storage=?, battery_health=?, condition_notes=?, purchase_price=?, asking_price=?, status=?, purchase_date=? WHERE id=?");
    $stmt->execute([$brand, $model, $device_type, $imei_serial, $storage, $battery_health, $condition_notes, $purchase_price, $asking_price, $status, $purchase_date, $id]);

    header('Location: inventory.php');
    exit;
    }
}
?>

<html>
<head>
    <title>Edit Device - Mobile Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4">Edit Device</h2>
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($device['brand']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Model</label>
            <input type="text" name="model" class="form-control" value="<?= htmlspecialchars($device['model']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Device Type</label>
            <select name="device_type" class="form-select" required>
                <option value="Phone" <?= $device['device_type'] === 'Phone' ? 'selected' : '' ?>>Phone</option>
                <option value="Laptop" <?= $device['device_type'] === 'Laptop' ? 'selected' : '' ?>>Laptop</option>
                <option value="Tablet" <?= $device['device_type'] === 'Tablet' ? 'selected' : '' ?>>Tablet</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">IMEI / Serial Number</label>
            <input type="text" name="imei_serial" class="form-control" value="<?= htmlspecialchars($device['imei_serial']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Storage</label>
            <input type="text" name="storage" class="form-control" value="<?= htmlspecialchars($device['storage']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Battery Health (%)</label>
            <input type="number" name="battery_health" class="form-control" min="0" max="100" value="<?= htmlspecialchars($device['battery_health'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Condition Notes</label>
            <textarea name="condition_notes" class="form-control" rows="3"><?= htmlspecialchars($device['condition_notes']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Purchase Price</label>
            <input type="number" step="0.01" name="purchase_price" class="form-control" value="<?= htmlspecialchars($device['purchase_price']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Asking Price</label>
            <input type="number" step="0.01" name="asking_price" class="form-control" value="<?= htmlspecialchars($device['asking_price']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Available" <?= $device['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
                <option value="Sold" <?= $device['status'] === 'Sold' ? 'selected' : '' ?>>Sold</option>
                <option value="Unavailable" <?= $device['status'] === 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" value="<?= htmlspecialchars($device['purchase_date']) ?>" required>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Update Device</button>
            <a href="inventory.php" class="btn btn-outline-secondary w-100">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>