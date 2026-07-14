<?php

require_once __DIR__ . "/BaseController.php";

class EventsController extends BaseController
{
    public function index(): void
    {
        $user = $this->requireUser();

        $this->render('events', [
            'user' => $user,
            'profilePath' => User::profilePicturePath($user['USER_ID']),
            'activeTab' => 'events',
        ]);
    }
}
