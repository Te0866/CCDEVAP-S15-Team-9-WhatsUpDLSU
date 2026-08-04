<?php
class UserModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function findById($userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM users WHERE USER_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function isUsernameTakenByAnotherUser($username, $userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT USER_ID FROM users WHERE USER_NAME = ? AND USER_ID != ?");
        mysqli_stmt_bind_param($stmt, "si", $username, $userId);
        mysqli_stmt_execute($stmt);
        $existing = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($existing) > 0;
    }

    public function updateOrganizationDetails($userId, $orgName, $password)
    {
        $stmt = mysqli_prepare($this->conn, "UPDATE users SET USER_NAME = ?, PASSWORD = ? WHERE USER_ID = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $orgName, $password, $userId);
        return mysqli_stmt_execute($stmt);
    }

    public function lastError()
    {
        return mysqli_error($this->conn);
    }
}
