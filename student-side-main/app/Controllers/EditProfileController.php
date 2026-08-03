<?php

require_once __DIR__ . "/BaseController.php";

class EditProfileController extends BaseController
{
    public function index(): void
    {
        $user = $this->requireUser();

        $this->render('edit-profile', [
            'user' => $user,
            'profilePath' => User::profilePicturePath($user['USER_ID']),
            'activeTab' => '',
        ]);
    }
}
