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

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports - Mobile Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/navbar.php'; ?>
<div class="container mt-5">
    <h2 class="mb-4">Reports</h2>
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-center p-3">
                <h6>Devices In Stock</h6>
                <h3><?= $in_stock ?></h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center p-3">
                <h6>Devices Sold</h6>
                <h3><?= $total_sold ?></h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center p-3">
                <h6>Total Revenue</h6>
                <h3><?= number_format($total_revenue, 2) ?></h3>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center p-3">
                <h6>Total Profit</h6>
                <h3><?= number_format($total_profit, 2) ?></h3>
            </div>
        </div>
    </div>
</div>
</body>
</html>