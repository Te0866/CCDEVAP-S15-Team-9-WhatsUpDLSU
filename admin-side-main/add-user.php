<?php
session_start();
require_once __DIR__ . '/controllers/AccountController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/admin-login.html");
    exit;
}

$adminName = $_SESSION['username'] ?? 'Moderator';

$userId = isset($_GET['id']) && $_GET['id'] !== '' ? (int) $_GET['id'] : null;

$controller = new AccountController();
$formData = $controller->getUserFormData($userId);

$mode = $formData['mode'];
$userId = $formData['userId'];
$username = $formData['username'];
$password = $formData['password'];
$errorMessage = isset($_SESSION['add_user_error']) ? $_SESSION['add_user_error'] : null;
unset($_SESSION['add_user_error']);

require __DIR__ . '/views/add-user-view.php';
