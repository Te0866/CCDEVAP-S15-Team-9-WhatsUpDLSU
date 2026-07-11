<?php
require_once __DIR__ . "/../dbconnection.php";

$sql = "
SELECT
    e.EVENT_ID,
    e.TITLE,
    e.CATEGORY,
    e.DESCRIPTION,
    e.VENUE,
    e.DATE,
    e.START_TIME,
    e.END_TIME,
    e.STATUS,
    e.REGISTRATION_STATUS,
    e.BANNER_IMAGE,
    o.ORG_NAME
FROM event e
JOIN organizations o
ON e.ORG_ID = o.ORG_ID
WHERE e.APPROVAL_STATUS = 'APPROVED'
ORDER BY e.DATE ASC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die(mysqli_error($conn));
}

$events = [];

while ($row = mysqli_fetch_assoc($result)) {

    $events[] = [
        "id" => $row["EVENT_ID"],
        "title" => $row["TITLE"],
        "category" => $row["CATEGORY"],
        "description" => $row["DESCRIPTION"],
        "venue" => $row["VENUE"],
        "date" => $row["DATE"],
        "startTime" => $row["START_TIME"],
        "endTime" => $row["END_TIME"],
        "status" => $row["STATUS"],
        "registration" => $row["REGISTRATION_STATUS"] ? "Open" : "Closed",
        "organizer" => $row["ORG_NAME"],
        "images" => [$row["BANNER_IMAGE"]],
        "comments" => []
    ];
}

header("Content-Type: application/json");
echo json_encode($events);
