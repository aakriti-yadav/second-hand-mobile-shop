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

$stmt = $pdo->query("
    SELECT s.sale_date, 
           SUM(s.selling_price) AS revenue,
           SUM(s.selling_price - d.purchase_price) AS profit
    FROM sales s
    JOIN devices d ON s.device_id = d.id
    GROUP BY s.sale_date
    ORDER BY s.sale_date
");
$sales_by_date = $stmt->fetchAll();
$chart_labels = json_encode(array_column($sales_by_date, 'sale_date'));
$chart_revenue = json_encode(array_column($sales_by_date, 'revenue'));
$chart_profit = json_encode(array_column($sales_by_date, 'profit'));

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports - Mobile Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
</head>
<body>
    <div class="app-shell">
        <?php include 'includes/navbar.php'; ?>
        <div class="main-content">
            <div class="page-header"><h2>Reports</h2></div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="stat-card accent-success">
                        <div class="stat-label">
                            Devices In Stock</div>
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
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-value"><?= number_format($total_revenue, 2) ?></div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card accent-primary">
                        <div class="stat-label">Total Profit</div>
                        <div class="stat-value"><?= number_format($total_profit, 2) ?></div>
                    </div>
                </div>
            </div>
            
            <div class="card p-3 mb-4">
                <h3 style="font-size:14px;" class="mb-3">Revenue & Profit Over Time</h3>
                <div style="position:relative;height:260px;">
                    <canvas id="revChart" role="img" aria-label="Line chart of revenue by sale date"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
    new Chart(document.getElementById('revChart'), {
        type: 'line',
        data: {
            labels: <?= $chart_labels ?>,
            datasets: [
                { label: 'Revenue', data: <?= $chart_revenue ?>, borderColor: '#4A6A8C', backgroundColor: 'rgba(74,106,140,0.1)', fill: true, tension: 0.3, pointRadius: 4 },
                { label: 'Profit', data: <?= $chart_profit ?>, borderColor: '#4A7A68', backgroundColor: 'rgba(74,122,104,0.1)', fill: true, tension: 0.3, pointRadius: 4 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: true } } }
    });
    </script>

</body>
</html>