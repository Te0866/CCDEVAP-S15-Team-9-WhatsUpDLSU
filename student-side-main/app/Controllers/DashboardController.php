<?php

require_once __DIR__ . "/../Models/User.php";

class DashboardController
{
    /**
     * Renders the dashboard page. Equivalent to the old dashboard.php,
     * minus the HTML — the HTML now lives in Views/dashboard.view.php.
     */
    public function index(): void
    {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header("Location: ../login-side-main/login.html");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = User::findById($userId);

        if (!$user) {
            die("User not found.");
        }

        // Data the view needs — nothing else touches $_SESSION or the DB
        // from here on.
        $viewData = [
            'user' => $user,
            'profilePath' => User::profilePicturePath($userId),
            'activeTab' => 'home',
        ];

        $this->render('dashboard', $viewData);
    }

    private function render(string $view, array $data): void
    {
        extract($data);
        require __DIR__ . "/../Views/{$view}.view.php";
    }
}
