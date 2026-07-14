<?php
session_start();
require_once __DIR__ . "/../dbconnection.php";
header("Content-Type: application/json");

$userId = $_SESSION['user_id'] ?? null;

$sql = "
SELECT
    e.EVENT_ID,
    e.TITLE,
    e.CATEGORY,
    e.DESCRIPTION,
    e.LOCATION,
    e.VENUE,
    e.DATE,
    e.START_TIME,
    e.END_TIME,
    e.STATUS,
    e.REGISTRATION_STATUS,
    e.BANNER_IMAGE,
    o.ORG_NAME,
    ei.INTEREST_ID
FROM event e
JOIN organizations o
    ON e.ORG_ID = o.ORG_ID
LEFT JOIN event_interest ei
    ON ei.EVENT_ID = e.EVENT_ID AND ei.USER_ID = ?
WHERE e.APPROVAL_STATUS = 'APPROVED'
ORDER BY e.DATE ASC, e.START_TIME ASC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo json_encode(["error" => mysqli_error($conn)]);
    exit;
}

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $images = [];
    if (!empty($row["BANNER_IMAGE"])) {
        $images[] = $row["BANNER_IMAGE"];
    }
    $events[] = [
        "id" => (int)$row["EVENT_ID"],
        "title" => $row["TITLE"],
        "category" => $row["CATEGORY"],
        "description" => $row["DESCRIPTION"],
        "venue" => $row["VENUE"],
        "location" => $row["LOCATION"],
        "date" => $row["DATE"],
        "startTime" => $row["START_TIME"],
        "endTime" => $row["END_TIME"],
        "status" => $row["STATUS"],
        "registration" => $row["REGISTRATION_STATUS"] ? "Open" : "Closed",
        "organizer" => $row["ORG_NAME"],
        "images" => $images,
        "comments" => [],
        "isInterested" => $row["INTEREST_ID"] !== null
    ];
}

echo json_encode($events);
?>
