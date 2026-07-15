<?php
require_once __DIR__ . "/BaseController.php";

class CalendarController extends BaseController
{
    public function index(): void
    {
        $user = $this->requireUser();
        $this->render('calendar', [
            'user' => $user,
            'profilePath' => User::profilePicturePath($user['USER_ID']),
            'activeTab' => 'calendar',
        ]);
    }
}