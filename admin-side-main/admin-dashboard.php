<?php
session_start();
require_once __DIR__ . '/controllers/EventController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../login-side-main/admin-login.html");
    exit;
}

$adminName = $_SESSION['username'] ?? 'Moderator';

$searchValue = isset($_GET['search']) ? trim($_GET['search']) : '';
$dateValue = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$categoryValue = isset($_GET['filter_category']) ? $_GET['filter_category'] : '';
$statusValue = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';

$controller = new EventController();
$dashboardData = $controller->getDashboardData([
    'search' => $searchValue,
    'date' => $dateValue,
    'category' => $categoryValue,
    'status' => $statusValue,
]);

$events = $dashboardData['events'];
$counts = $dashboardData['counts'];
$orgsCount = $dashboardData['orgsCount'];

require __DIR__ . '/views/admin-dashboard-view.php';
