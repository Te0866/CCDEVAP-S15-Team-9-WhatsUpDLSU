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

$userId    = $_POST['user_id'] ?? null;
$orgName  = $_POST['org_name'] ?? '';
$password = $_POST['password'] ?? '';

$controller = new AccountController();
$result = $controller->saveOrganization($userId, $orgName, $password);

$hasUserId = $userId !== null && $userId !== '';

if (!$result['success']) {
    $_SESSION['admin_create_error'] = $result['error'];
    $redirect = $hasUserId ? "admin-create.php?id=" . urlencode($userId) : "admin-create.php";  // CHANGED
    header("Location: " . $redirect);
    exit;
}

$_SESSION['success_message'] = $hasUserId
    ? "Organization updated successfully!" 
    : "Organization created successfully!";

header("Location: admin-create.php" . ($hasUserId ? "?id=" . urlencode($userId) : ""));  // CHANGED
exit;