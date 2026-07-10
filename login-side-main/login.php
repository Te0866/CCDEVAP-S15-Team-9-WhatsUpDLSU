<?php
session_start();
require "../dbconnection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE user_name = ? AND STATUS = 'ACTIVE'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["user_name"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "ADMIN") {
                header("Location: admin-side-main/admin-dashboard.php");
            }
            elseif ($user["role"] == "OFFICER") {
                header("Location: org-side-main/officer-dashboard.php");
            }
            else {
                header("Location: student-side-main/dashboard.php");
            }

            exit();
        }
        else {
            echo "Incorrect password.";
        }

    } else {
        echo "User not found or account inactive.";
    }

    $stmt->close();
}

$conn->close();
?>
