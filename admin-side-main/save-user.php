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

$userId   = $_POST['user_id'] ?? null;
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$controller = new AccountController();
$result = $controller->saveUser($userId, $username, $password);

if (!$result['success']) {
    $_SESSION['add_user_error'] = $result['error'];
    $redirect = $userId ? "add-user.php?id=" . urlencode($userId) : "add-user.php";
    header("Location: " . $redirect);
    exit;
}

$_SESSION['success_message'] = $userId 
    ? "User updated successfully!" 
    : "User created successfully!";

header("Location: add-user.php" . ($userId ? "?id=" . urlencode($userId) : ""));
exit;