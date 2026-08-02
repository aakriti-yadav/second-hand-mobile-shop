<div class="sidebar">
    <div class="sidebar-brand">Mobile Shop</div>
    <nav class="sidebar-nav">
        <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="inventory.php" class="<?= basename($_SERVER['PHP_SELF']) === 'inventory.php' ? 'active' : '' ?>">Inventory</a>
        <a href="add_device.php" class="<?= basename($_SERVER['PHP_SELF']) === 'add_device.php' ? 'active' : '' ?>">Add Device</a>
        <a href="sales.php" class="<?= basename($_SERVER['PHP_SELF']) === 'sales.php' ? 'active' : '' ?>">Sales</a>
        <a href="reports.php" class="<?= basename($_SERVER['PHP_SELF']) === 'reports.php' ? 'active' : '' ?>">Reports</a>
        <a href="logout.php">Logout</a>
    </nav>
</div>