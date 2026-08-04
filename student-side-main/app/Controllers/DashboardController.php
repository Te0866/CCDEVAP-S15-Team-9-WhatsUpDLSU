<?php

require_once __DIR__ . "/BaseController.php";

class DashboardController extends BaseController
{
    public function index(): void
    {
        $user = $this->requireUser();

        $this->render('dashboard', [
            'user' => $user,
            'profilePath' => User::profilePicturePath($user['USER_ID']),
            'activeTab' => 'home',
        ]);
    }
}
