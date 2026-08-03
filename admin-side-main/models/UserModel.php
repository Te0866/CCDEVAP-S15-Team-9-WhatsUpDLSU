<?php
require_once __DIR__ . '/Database.php';

class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }
    
    public function getManagedAccounts($search = null, $type = null) {
        $sql = "SELECT u.USER_ID, u.USER_NAME, u.ROLE, u.CREATED_AT, u.STATUS
                FROM users u
                WHERE u.ROLE IN ('USER', 'OFFICER')";

        $params = [];
        $types = "";

        if ($type === 'student') {
            $sql .= " AND u.ROLE = 'USER'";
        } else if ($type === 'organization') {
            $sql .= " AND u.ROLE = 'OFFICER'";
        }

        if ($search !== null && $search !== '') {
            $sql .= " AND u.USER_NAME LIKE ?";
            $params[] = "%" . $search . "%";
            $types .= "s";
        }

        $sql .= " ORDER BY u.CREATED_AT DESC, u.USER_ID DESC";

        $stmt = mysqli_prepare($this->conn, $sql);

        if ($types !== "") {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getUserById($userId) {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM users WHERE USER_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result) ?: null;
    }

    // Check if Username exists
    public function usernameExists($username, $excludeUserId = null) {
        if ($excludeUserId !== null) {
            $stmt = mysqli_prepare($this->conn, "SELECT USER_ID FROM users WHERE USER_NAME = ? AND USER_ID != ?");
            mysqli_stmt_bind_param($stmt, "si", $username, $excludeUserId);
        } else {
            $stmt = mysqli_prepare($this->conn, "SELECT USER_ID FROM users WHERE USER_NAME = ?");
            mysqli_stmt_bind_param($stmt, "s", $username);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_num_rows($result) > 0;
    }

    // Creating users
    public function createUser($username, $password, $role, $status = 'ACTIVE') {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO users 
            (USER_NAME, PASSWORD, ROLE, CREATED_AT, STATUS) 
            VALUES (?, ?, ?, CURDATE(), ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $username, $password, $role, $status);
        mysqli_stmt_execute($stmt);

        return mysqli_insert_id($this->conn);
    }

    // Updating user info
    public function updateUser($userId, $username, $password = null, $status = null) {
        if ($password !== null && $password !== '') {
            $stmt = mysqli_prepare($this->conn, "UPDATE users 
                SET USER_NAME = ?, PASSWORD = ?, STATUS = COALESCE(?, STATUS) 
                WHERE USER_ID = ?");
            mysqli_stmt_bind_param($stmt, "sssi", $username, $password, $status, $userId);
        } else {
            $stmt = mysqli_prepare($this->conn, "UPDATE users 
                SET USER_NAME = ?, STATUS = COALESCE(?, STATUS) 
                WHERE USER_ID = ?");
            mysqli_stmt_bind_param($stmt, "ssi", $username, $status, $userId);
        }

        return mysqli_stmt_execute($stmt);
    }

    public function deleteUser($userId) {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM users WHERE USER_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        return mysqli_stmt_execute($stmt);
    }

    public function getTotalOfficers() {
        $result = mysqli_query($this->conn, "SELECT COUNT(*) AS total FROM users WHERE ROLE = 'OFFICER'");
        $row = mysqli_fetch_assoc($result);
        return (int) $row['total'];
    }
}
