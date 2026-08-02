<?php
session_start();
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Mobile Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background: var(--color-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: var(--color-surface);
            border-radius: 12px;
            padding: 40px 36px;
            width: 380px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        .login-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .login-brand-dot {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            background: var(--color-accent);
        }
        .login-brand-text {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.02em;
        }
        .login-subtitle {
            color: var(--color-ink-muted);
            font-size: 13px;
            margin-bottom: 28px;
        }
        .login-btn {
            width: 100%;
            padding: 11px;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-brand">
            <span class="login-brand-dot"></span>
            <span class="login-brand-text">MOBILE SHOP</span>
        </div>
        <div class="login-subtitle">Inventory & Sales Management</div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger py-2 px-3" style="font-size: 13px;"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary login-btn">Sign In</button>
        </form>
    </div>
</body>
</html>