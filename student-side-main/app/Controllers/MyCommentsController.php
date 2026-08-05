<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/Comment.php";

class MyCommentsController extends BaseController
{
    public function index(): void
    {
        $user = $this->requireUser();
        $this->render('my-comments', [
            'comments' => Comment::findByUsername($user['USER_NAME']),
            'profilePath' => User::profilePicturePath($user['USER_ID']),
            'activeTab' => '',
        ]);
    }
}
