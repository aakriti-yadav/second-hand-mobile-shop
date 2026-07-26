<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';

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
    $purchase_date = $_POST['purchase_date'];

    $stmt = $pdo->prepare("INSERT INTO devices (brand, model, device_type, imei_serial, storage, battery_health, condition_notes, purchase_price, asking_price, purchase_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$brand, $model, $device_type, $imei_serial, $storage, $battery_health, $condition_notes, $purchase_price, $asking_price, $purchase_date]);

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Device - Mobile Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4">Add New Device</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Model</label>
            <input type="text" name="model" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Device Type</label>
            <select name="device_type" class="form-select" required>
                <option value="">Select type</option>
                <option value="Phone">Phone</option>
                <option value="Laptop">Laptop</option>
                <option value="Tablet">Tablet</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">IMEI / Serial Number</label>
            <input type="text" name="imei_serial" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Storage</label>
            <input type="text" name="storage" class="form-control" placeholder="e.g. 128GB" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Battery Health (%) - leave blank if not applicable</label>
            <input type="number" name="battery_health" class="form-control" min="0" max="100">
        </div>
        <div class="mb-3">
            <label class="form-label">Condition Notes</label>
            <textarea name="condition_notes" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Purchase Price</label>
            <input type="number" step="0.01" name="purchase_price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Asking Price</label>
            <input type="number" step="0.01" name="asking_price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Add Device</button>
    </form>
</div>
</body>
</html>