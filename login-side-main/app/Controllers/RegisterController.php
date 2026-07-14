<?php

require_once __DIR__ . "/../Models/User.php";

class RegisterController
{
    public function store(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true) ?? [];

        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirmPassword'] ?? '';

        // Validation
        if ($username === '' || $password === '') {
            $this->json(["success" => false, "error" => "All fields are required."]);
            return;
        }

        if (strlen($password) < 8) {
            $this->json(["success" => false, "error" => "Password must be at least 8 characters."]);
            return;
        }

        if ($password !== $confirmPassword) {
            $this->json(["success" => false, "error" => "Passwords do not match."]);
            return;
        }

        if (User::usernameExists($username)) {
            $this->json(["success" => false, "error" => "That username is already taken."]);
            return;
        }

        $userId = User::create($username, $password);

        if ($userId === false) {
            $this->json(["success" => false, "error" => "Could not create account. Please try again."]);
            return;
        }

        $this->json(["success" => true, "userId" => $userId]);
    }

    private function json(array $data): void
    {
        echo json_encode($data);
    }
}
