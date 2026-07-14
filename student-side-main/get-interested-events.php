<?php
session_start();
require_once __DIR__ . "/../dbconnection.php";

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "
SELECT
    e.EVENT_ID,
    e.TITLE,
    e.CATEGORY,
    e.DATE,
    e.BANNER_IMAGE
FROM event_interest ei
INNER JOIN event e
    ON ei.EVENT_ID = e.EVENT_ID
WHERE ei.USER_ID = ?
ORDER BY e.DATE ASC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    $events[] = [
        "id" => $row["EVENT_ID"],
        "title" => $row["TITLE"],
        "category" => $row["CATEGORY"],
        "date" => $row["DATE"],
        "image" => $row["BANNER_IMAGE"]
    ];
}

echo json_encode($events);
