<?php
require_once __DIR__ . "/../dbconnection.php";
header('Content-Type: application/json');

$sql = "SELECT EVENT_ID, TITLE, CATEGORY, DATE, START_TIME, LOCATION
        FROM event
        WHERE APPROVAL_STATUS = 'APPROVED'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["error" => mysqli_error($conn)]);
    exit;
}

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = [
        "id" => (int) $row["EVENT_ID"],
        "title" => $row["TITLE"],
        "category" => $row["CATEGORY"],       // 'ACADEMIC' / 'NON-ACADEMIC' / 'CAREER'
        "date" => $row["DATE"],               // 'YYYY-MM-DD'
        "time" => $row["START_TIME"],         // 'HH:MM:SS'
        "location" => $row["LOCATION"]
    ];
}

echo json_encode($events);
?>