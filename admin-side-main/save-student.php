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

$userId          = $_POST['user_id'] ?? null;
$username        = $_POST['username'] ?? '';
$password        = $_POST['password'] ?? '';
$retypePassword  = $_POST['retype_password'] ?? '';

if ($password !== $retypePassword) {
    $_SESSION['add_student_error'] = "Passwords do not match. Please try again.";
    $redirect = $userId ? "add-student.php?id=" . urlencode($userId) : "add-student.php";
    header("Location: " . $redirect);
    exit;
}

$controller = new AccountController();
$result = $controller->saveUser($userId, $username, $password);

if (!$result['success']) {
    $_SESSION['add_student_error'] = $result['error'];
    $redirect = $userId ? "add-student.php?id=" . urlencode($userId) : "add-student.php";
    header("Location: " . $redirect);
    exit;
}

$_SESSION['success_message'] = $userId
    ? "Student updated successfully!"
    : "Student created successfully!";

header("Location: add-student.php" . ($userId ? "?id=" . urlencode($userId) : ""));
exit;
