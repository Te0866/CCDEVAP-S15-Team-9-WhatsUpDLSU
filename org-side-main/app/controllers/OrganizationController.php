<?php
class OrganizationController
{
    private $conn;
    private $userModel;

    public function __construct()
    {
        $this->conn = Database::connection();
        $this->userModel = new UserModel($this->conn);
    }

    public function edit()
    {
        Auth::requireOfficer();

        $userId = Auth::currentUserId();

        $user = $this->userModel->findById($userId);
        if (!$user) {
            die("User not found.");
        }

        $profilePath = ProfilePicture::resolve($this->conn, $user, $userId);

        require __DIR__ . "/../views/edit-organization.view.php";
    }

    public function update()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'OFFICER') {
            $this->respond(["success" => false, "error" => "Not logged in."]);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->respond(["success" => false, "error" => "Invalid request method."]);
        }

        $userId = $_SESSION['user_id'];

        $orgName = trim($_POST['orgName'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($orgName === '' || $password === '') {
            $this->respond(["success" => false, "error" => "Organization name and password are required."]);
        }

        if ($this->userModel->isUsernameTakenByAnotherUser($orgName, $userId)) {
            $this->respond(["success" => false, "error" => "That organization name is already taken. Please choose another."]);
        }

        if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/png' => 'png', 'image/jpeg' => 'jpg'];
            $mimeType = mime_content_type($_FILES['profileImage']['tmp_name']);

            if (!isset($allowedTypes[$mimeType])) {
                $this->respond(["success" => false, "error" => "Only PNG and JPG images are allowed."]);
            }

            $extension = $allowedTypes[$mimeType];
            $targetDir = __DIR__ . "/../../../profile-pictures/{$userId}/";

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            foreach (['png', 'jpg'] as $ext) {
                $existingFile = $targetDir . "pfp.{$ext}";
                if (file_exists($existingFile)) {
                    unlink($existingFile);
                }
            }

            $targetPath = $targetDir . "pfp.{$extension}";

            if (!move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetPath)) {
                $this->respond(["success" => false, "error" => "Failed to save uploaded image."]);
            }
        }

        $success = $this->userModel->updateOrganizationDetails($userId, $orgName, $password);

        if ($success) {
            $_SESSION['username'] = $orgName;
            $this->respond(["success" => true]);
        } else {
            $this->respond(["success" => false, "error" => "Update failed: " . $this->userModel->lastError()]);
        }
    }

    private function respond($data)
    {
        echo json_encode($data);
        exit;
    }
}
