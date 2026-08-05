<?php

require_once __DIR__ . "/../Models/User.php";

/**
 * Any controller that renders a logged-in page (dashboard, events,
 * calendar, ...) extends this instead of re-writing the same session
 * check + user fetch every time.
 */
abstract class BaseController
{
    /**
     * Starts the session, redirects to login if not authenticated,
     * and returns the current user's row. Dies if the user row is missing
     * (e.g. deleted account with a stale session).
     */
    protected function requireUser(): array
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: ../login-side-main/login.html");
            exit;
        }

        $user = User::findById($_SESSION['user_id']);

        if (!$user) {
            die("User not found.");
        }

        return $user;
    }

    protected function render(string $view, array $data): void
    {
        extract($data);
        require __DIR__ . "/../Views/{$view}.view.php";
    }
}
