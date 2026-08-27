<?php
session_start();

// To change the password, generate a new hash:
//   php -r "echo password_hash('your-new-password', PASSWORD_DEFAULT);"
// Then replace the hash below.
$ADMIN_PASSWORD_HASH = '$2y$10$2hu1kUby91devmoKyUaxAuOKd.fCPb6BqvjVRuk5bafQdo0hzNC1G';

function is_logged_in(): bool {
    return !empty($_SESSION['admin']);
}

function require_auth(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function attempt_login(string $password): bool {
    global $ADMIN_PASSWORD_HASH;
    if (password_verify($password, $ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin'] = true;
        return true;
    }
    return false;
}

function logout(): void {
    session_destroy();
}
