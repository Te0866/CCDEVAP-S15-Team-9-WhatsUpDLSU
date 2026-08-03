<?php
session_start();
require_once __DIR__ . '/controllers/AccountController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/admin-login.html");
    exit;
}

$adminName = $_SESSION['username'] ?? 'Moderator';

$searchValue = isset($_GET['search']) ? trim($_GET['search']) : '';
$typeValue = isset($_GET['type']) ? $_GET['type'] : 'all';

$controller = new AccountController();
$accounts = $controller->listAccounts(
    $searchValue !== '' ? $searchValue : null,
    $typeValue !== 'all' ? $typeValue : null
);

require __DIR__ . '/views/account-management-view.php';
