<?php
die("Index.php is being executed!");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to a file (optional but helpful)
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

echo "<!-- Debug: Starting index.php -->\n";

session_start();
require_once __DIR__ . "/../dbconnection.php";

// Autoloader
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require $file;
});

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-side-main/login.html");
    exit;
}

// Role check - only STUDENT allowed
if ($_SESSION['role'] !== 'STUDENT') {
    if ($_SESSION['role'] === 'ADMIN') {
        header("Location: ../admin-side-main/index.php");
    } elseif ($_SESSION['role'] === 'OFFICER') {
        header("Location: ../org-side-main/index.php");
    }
    exit;
}

// Get page
$page = $_GET['page'] ?? 'dashboard';

switch($page) {
    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
    case 'events':
        $controller = new EventController();
        $controller->index();
        break;
    case 'event':
        $id = $_GET['id'] ?? null;
        $controller = new EventController();
        $controller->show($id);
        break;
    case 'calendar':
        $controller = new CalendarController();
        $controller->index();
        break;
    case 'edit-profile':
        $controller = new UserController();
        $controller->edit();
        break;
    case 'update-profile':
        $controller = new UserController();
        $controller->update();
        break;
    case 'api-interested':
        $controller = new DashboardController();
        $controller->getInterestedEvents();
        break;
    case 'api-popular':
        $controller = new DashboardController();
        $controller->getPopularEvents();
        break;
    case 'api-category':
        $controller = new DashboardController();
        $controller->getCategoryStats();
        break;
    case 'api-toggle':
        $controller = new EventController();
        $controller->toggleInterest();
        break;
    case 'logout':
        header("Location: ../login-side-main/logout.php");
        exit;
    default:
        header("Location: ?page=dashboard");
        exit;
}
?>
