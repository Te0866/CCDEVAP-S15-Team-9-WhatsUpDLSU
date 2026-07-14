<?php
// Force all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Add this to see if there's any output buffering issue
ob_implicit_flush(true);

echo "<!-- DEBUG: Script started -->\n";

session_start();
echo "<!-- DEBUG: Session started -->\n";

require_once __DIR__ . "/../dbconnection.php";
echo "<!-- DEBUG: dbconnection loaded -->\n";

if (!isset($_SESSION['user_id'])) {
    echo "<!-- DEBUG: Not logged in -->\n";
    header("Location: ../login-side-main/login.html");
    exit;
}
echo "<!-- DEBUG: User logged in (ID: " . $_SESSION['user_id'] . ") -->\n";

if ($_SESSION['role'] !== 'STUDENT') {
    echo "<!-- DEBUG: Role is " . $_SESSION['role'] . " -->\n";
    if ($_SESSION['role'] === 'ADMIN') {
        header("Location: ../admin-side-main/index.php");
    } elseif ($_SESSION['role'] === 'OFFICER') {
        header("Location: ../org-side-main/index.php");
    }
    exit;
}
echo "<!-- DEBUG: Role is STUDENT -->\n";

// Autoloader
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    echo "<!-- DEBUG: Autoloader trying: " . $file . " -->\n";
    if (file_exists($file)) {
        echo "<!-- DEBUG: Found: " . $file . " -->\n";
        require $file;
    } else {
        echo "<!-- DEBUG: NOT Found: " . $file . " -->\n";
    }
});

// Get page
$page = $_GET['page'] ?? 'dashboard';
echo "<!-- DEBUG: Page = " . $page . " -->\n";

// Check if controller file exists before calling
$controllerFile = __DIR__ . "/controlers/" . ucfirst($page) . "Controller.php";
if ($page === 'dashboard' || $page === 'events' || $page === 'calendar' || $page === 'edit-profile') {
    $controllerName = ucfirst($page) . 'Controller';
    echo "<!-- DEBUG: Looking for controller: " . $controllerName . " -->\n";
    echo "<!-- DEBUG: Controller file: " . $controllerFile . " -->\n";
    
    if (!file_exists($controllerFile)) {
        die("ERROR: Controller file not found: " . $controllerFile);
    }
}

try {
    switch($page) {
        case 'dashboard':
            echo "<!-- DEBUG: Creating DashboardController -->\n";
            $controller = new DashboardController();
            echo "<!-- DEBUG: Calling index() -->\n";
            $controller->index();
            break;
        case 'events':
            echo "<!-- DEBUG: Creating EventController -->\n";
            $controller = new EventController();
            echo "<!-- DEBUG: Calling index() -->\n";
            $controller->index();
            break;
        case 'event':
            $id = $_GET['id'] ?? null;
            echo "<!-- DEBUG: Creating EventController (show) -->\n";
            $controller = new EventController();
            $controller->show($id);
            break;
        case 'calendar':
            echo "<!-- DEBUG: Creating CalendarController -->\n";
            $controller = new CalendarController();
            $controller->index();
            break;
        case 'edit-profile':
            echo "<!-- DEBUG: Creating UserController (edit) -->\n";
            $controller = new UserController();
            $controller->edit();
            break;
        case 'update-profile':
            echo "<!-- DEBUG: Creating UserController (update) -->\n";
            $controller = new UserController();
            $controller->update();
            break;
        case 'api-interested':
            echo "<!-- DEBUG: Creating DashboardController (api) -->\n";
            $controller = new DashboardController();
            $controller->getInterestedEvents();
            break;
        case 'api-popular':
            echo "<!-- DEBUG: Creating DashboardController (api) -->\n";
            $controller = new DashboardController();
            $controller->getPopularEvents();
            break;
        case 'api-category':
            echo "<!-- DEBUG: Creating DashboardController (api) -->\n";
            $controller = new DashboardController();
            $controller->getCategoryStats();
            break;
        case 'api-toggle':
            echo "<!-- DEBUG: Creating EventController (api) -->\n";
            $controller = new EventController();
            $controller->toggleInterest();
            break;
        case 'logout':
            echo "<!-- DEBUG: Logging out -->\n";
            header("Location: ../login-side-main/logout.php");
            exit;
        default:
            echo "<!-- DEBUG: Unknown page, redirecting -->\n";
            header("Location: ?page=dashboard");
            exit;
    }
} catch (Exception $e) {
    echo "<!-- ERROR: " . $e->getMessage() . " -->\n";
    echo "<!-- ERROR: " . $e->getTraceAsString() . " -->\n";
    die("Error: " . $e->getMessage());
} catch (Error $e) {
    echo "<!-- FATAL ERROR: " . $e->getMessage() . " -->\n";
    echo "<!-- FATAL ERROR: " . $e->getTraceAsString() . " -->\n";
    die("Fatal Error: " . $e->getMessage());
}

echo "<!-- DEBUG: Script completed -->\n";
?>
