<?php
session_start();
require_once __DIR__ . "/../dbconnection.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Please log in."
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$userId = $_SESSION['user_id'];
$eventId = intval($data['event_id']);

if ($eventId <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid event."
    ]);
    exit;
}


$check = mysqli_prepare($conn,
"SELECT INTEREST_ID
 FROM event_interest
 WHERE USER_ID = ?
 AND EVENT_ID = ?");

mysqli_stmt_bind_param($check,"ii",$userId,$eventId);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if(mysqli_stmt_num_rows($check) > 0){

    echo json_encode([
        "success" => false,
        "message" => "Already marked as interested."
    ]);
    exit;
}


$stmt = mysqli_prepare($conn,
"INSERT INTO event_interest(USER_ID, EVENT_ID)
VALUES (?, ?)");

mysqli_stmt_bind_param($stmt,"ii",$userId,$eventId);

if(mysqli_stmt_execute($stmt)){

    echo json_encode([
        "success" => true,
        "message" => "Added to Interested Events!"
    ]);

}else{

    echo json_encode([
        "success" => false,
        "message" => mysqli_error($conn)
    ]);

}
