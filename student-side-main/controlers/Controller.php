<?php
class Controller {
    
    protected function render($view, $data = []) {
        extract($data);
        ob_start();
        include __DIR__ . "/../views/{$view}.php";
        $content = ob_get_clean();
        include __DIR__ . "/../views/layout.php";
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
            header("Location: login-side-main/login.html");
            exit;
        }
    }
}
