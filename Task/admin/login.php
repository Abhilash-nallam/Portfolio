<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

// Basic brute-force throttling
if (empty($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Your session expired. Please try again.';
    } elseif ($_SESSION['login_attempts'] >= 6) {
        $error = 'Too many failed attempts. Please wait a minute and try again.';
    } else {
        $username = clean_input($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = 'Please enter both username and password.';
        } else {
            try {
                $pdo  = getDbConnection();
                $stmt = $pdo->prepare('SELECT id, username, password, full_name FROM admins WHERE username = :username LIMIT 1');
                $stmt->execute(['username' => $username]);
                $admin = $stmt->fetch();

                if ($admin && password_verify($password, $admin['password'])) {
                    $_SESSION['login_attempts'] = 0;
                    login_admin($admin);
                    header('Location: dashboard.php');
                    exit;
                }

                $_SESSION['login_attempts']++;
                $error = 'Invalid username or password.';
            } catch (Throwable $e) {
    die($e->getMessage());
}
        }
    }
}

$expired = isset($_GET['expired']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login &mdash; LeadDesk Mini</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../css/style.css">
</head>
<body class="login-body">

<div class="login-wrap">
    <div class="login-card">
        <div class="login-brand">
            <div class="login-logo"><i class="fa-solid fa-chart-line"></i></div>
            <h1>LeadDesk <span>Mini</span></h1>
            <p>Sign in to manage your leads pipeline</p>
        </div>

        <?php if ($expired): ?>
            <div class="alert alert-warning small mb-3"><i class="fa-solid fa-clock me-1"></i> Your session timed out. Please sign in again.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger small mb-3"><i class="fa-solid fa-circle-exclamation me-1"></i> <?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <div class="input-icon">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" class="form-control" id="username" name="username" placeholder="admin" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <div class="input-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" class="form-control" id="password" name="password" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login w-100">
                <i class="fa-solid fa-right-to-bracket me-2"></i>Sign In
            </button>
        </form>

        <div class="login-hint">
            Demo credentials &mdash; <strong>admin</strong> / <strong>Admin@123</strong>
        </div>

        <a href="../index.php" class="back-link"><i class="fa-solid fa-arrow-left me-1"></i> Back to website</a>
    </div>
</div>

</body>
</html>
