<?php

require_once __DIR__ . "/../Models/User.php";

class ProfileController
{
    
    public function update(): void
    {
        session_start();
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            $this->json(["success" => false, "error" => "Not logged in"]);
            return;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $userId = $_SESSION['user_id'];

        if ($username === '') {
            $this->json(["success" => false, "error" => "Username is required"]);
            return;
        }

        if (User::usernameExists($username, $userId)) {
            $this->json(["success" => false, "error" => "That username is already taken."]);
            return;
        }

        if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            [$ok, $error] = User::saveProfileImage($userId, $_FILES['profileImage']);

            if (!$ok) {
                $this->json(["success" => false, "error" => $error]);
                return;
            }
        }

        $success = User::updateProfile($userId, $username, $password);

        if ($success) {
            $this->json(["success" => true]);
        } else {
            $this->json(["success" => false, "error" => "Update failed"]);
        }
    }

    private function json(array $data): void
    {
        echo json_encode($data);
    }
}
