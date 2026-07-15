<?php

class Auth
{
    public static function requireOfficer()
    {
        // Prevent the browser (and its back/forward cache) from serving a
        // cached copy of this authenticated page after the session is gone,
        // e.g. after logout + back button.
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'OFFICER') {
            header("Location: ../login-side-main/login.html");
            exit;
        }
    }

    public static function currentUserId()
    {
        return $_SESSION['user_id'];
    }

    public static function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }
}
