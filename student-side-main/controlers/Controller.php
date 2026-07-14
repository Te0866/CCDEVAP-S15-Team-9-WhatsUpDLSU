<?php
class Controller {
    protected $profilePath = 'img/default-profile.png';
    
    protected function render($view, $data = []) {
        extract($data);
        
        require_once __DIR__ . '/../profile-picture.php';
        $profilePath = $profilePath ?? 'img/default-profile.png';
        $activeTab = $data['activeTab'] ?? 'home';
        
        ob_start();
        $viewFile = __DIR__ . "/../views/{$view}.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        }
        $content = ob_get_clean();
        
        require_once __DIR__ . '/../navbar.php';
        include __DIR__ . "/../views/layouts/main.php";
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    protected function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../login-side-main/login.html");
            exit;
        }
    }
}
