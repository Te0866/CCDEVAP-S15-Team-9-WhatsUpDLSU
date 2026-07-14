
<?php

session_start();

require_once "../../dbconnection.php";
require_once "../models/UserModel.php";

if(!isset($_SESSION["user_id"]))
{
    header("Location: ../../login-side-main/login.html");
    exit;
}

$userModel=new UserModel($conn);

$user=$userModel->getUserById($_SESSION["user_id"]);

require "../views/dashboard.php";
