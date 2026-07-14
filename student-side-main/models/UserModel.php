<?php

class UserModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getUserById($userId)
    {
        $stmt = mysqli_prepare(
            $this->conn,
            "SELECT * FROM users WHERE USER_ID=?"
        );

        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);

        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }
}
