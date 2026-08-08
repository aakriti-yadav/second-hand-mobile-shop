<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require 'config/db.php';

$stmt = $pdo->query("SELECT * FROM devices WHERE status = 'Available' ORDER BY brand, model");
$available_devices = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_id = $_POST['device_id'];
    $buyer_name = $_POST['buyer_name'];
    $selling_price = $_POST['selling_price'];
    $sale_date = $_POST['sale_date'];

    $errors = [];

    if (empty($device_id)) {
        $errors[] = "Please select a device.";
    }
    if ($selling_price <= 0) {
        $errors[] = "Selling price must be greater than 0.";
    }

    if (empty($errors)) { 
    $stmt = $pdo->prepare("INSERT INTO sales (device_id, buyer_name, selling_price, sale_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$device_id, $buyer_name, $selling_price, $sale_date]);

    $stmt2 = $pdo->prepare("UPDATE devices SET status = 'Sold' WHERE id = ?");
    $stmt2->execute([$device_id]);

    header('Location: inventory.php');
    exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Sale - Mobile Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet"> 
</head>
<body>
    <div class="app-shell">
        <?php include 'includes/navbar.php'; ?>
        <div class="main-content">
            <div class="page-header"><h2>Record Sale</h2></div>
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <div class="d-flex justify-content-center">
                <div class="card p-4" style="width: 820px;">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Select Device</label>
                            <select name="device_id" class="form-select" required>
                                <option value="">Select a device</option>
                                <?php foreach ($available_devices as $d): ?>
                                    <option value="<?= $d['id'] ?>">
                                        <?= htmlspecialchars($d['brand'] . ' ' . $d['model'] . ' - ' . $d['imei_serial']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">Buyer Name (optional)</label>
                                <input type="text" name="buyer_name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Selling Price</label>
                                <input type="number" step="0.01" name="selling_price" class="form-control" required>
                            </div>
                        </div>
                            <div class="mb-3">
                                <label class="form-label">Sale Date</label>
                                <input type="date" name="sale_date" class="form-control" required>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">Record Sale</button>
                                <a href="inventory.php" class="btn btn-outline-secondary w-100">Cancel</a>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>