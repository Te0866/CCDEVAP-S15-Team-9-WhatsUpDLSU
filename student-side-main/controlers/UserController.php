<?php
require_once __DIR__ . "/Controller.php";

class UserController extends Controller {
    
    public function logout() {
        session_destroy();
        header("Location: login-side-main/login.html");
        exit;
    }
}
