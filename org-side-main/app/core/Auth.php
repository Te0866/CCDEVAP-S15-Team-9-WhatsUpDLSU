<?php

class Auth
{
    public static function requireOfficer()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'OFFICER') {
            header("Location: ../login-side-main/officer-login.html");
            exit;
        }
    }

    public static function currentUserId()
    {
        return $_SESSION['user_id'];
    }
}
