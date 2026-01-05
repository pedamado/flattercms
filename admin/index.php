<?php
// Define the admin constant before including config
define('IN_ADMIN', true);
require_once __DIR__ . '/config.php';

// Redirect if already logged in
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic brute-force protection
    if ($_SESSION['login_attempts'] >= LOGIN_ATTEMPTS_LIMIT) {
        $error = 'Too many failed attempts. Try again later.';
    } elseif ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        // Successful login
        $_SESSION['authenticated'] = true;
        $_SESSION['login_time'] = time();
        $_SESSION['login_attempts'] = 0;
        header('Location: dashboard.php');
        exit;
    } else {
        // Failed attempt
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CMS Admin Login</title>
    <style>
        .login-form {
            max-width: 400px;
            margin: 2rem auto;
            padding: 2rem;
            border: 1px solid #ddd;
        }

        .error {
            color: #dc3545;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="login-form">
        <h1>CMS Admin Login</h1>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>

            <div>
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>