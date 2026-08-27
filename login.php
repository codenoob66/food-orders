<?php
require_once __DIR__ . '/auth.php';

// Already logged in? Go to admin
if (is_logged_in()) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    if (attempt_login($password)) {
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Wrong password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Admin Login</title>
</head>

<body>
    <div class="top-section">
        <h1>🔒</h1>
    </div>
    <div class="form-card">
        <p>Enter the admin password to continue.</p>
        <?php if ($error): ?>
            <p class="status-message status-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required autofocus>
            <button id="upload-button" type="submit">Login</button>
        </form>
    </div>
</body>

</html>
