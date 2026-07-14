<?php
session_start();
require_once __DIR__ . '/controllers/AccountController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/admin-login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: account-management.php");
    exit;
}

$type = $_POST['type'] ?? '';
$id   = $_POST['id'] ?? '';

if (($type === 'student' || $type === 'organization') && $id !== '') {
    $controller = new AccountController();
    $success = $controller->deleteAccount($type, $id);

    if ($success) {
        $_SESSION['success_message'] = "Account deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete account.";
    }
}

header("Location: account-management.php");
exit;