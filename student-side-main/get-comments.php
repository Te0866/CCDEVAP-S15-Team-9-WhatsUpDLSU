<?php
require_once __DIR__ . "/../dbconnection.php";
header("Content-Type: application/json");

$eventId = intval($_GET['event_id'] ?? 0);

if ($eventId <= 0) {
    echo json_encode(["error" => "Invalid event id"]);
    exit;
}

$currentUsername = $_SESSION['username'] ?? null;
$stmt = mysqli_prepare($conn, "SELECT COMMENT_ID, USERNAME, TEXT, IS_ANONYMOUS FROM comments WHERE EVENT_ID = ? ORDER BY COMMENT_ID ASC");
mysqli_stmt_bind_param($stmt, "i", $eventId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$comments = [];
while ($row = mysqli_fetch_assoc($result)) {
    $comments[] = [
        "id" => (int)$row["COMMENT_ID"],
        "author" => $row["IS_ANONYMOUS"] ? "Anonymous" : $row["USERNAME"],
        "text" => $row["TEXT"],
        "isOwner" => $currentUsername !== null && $row["USERNAME"] === $currentUsername
    ];
}

echo json_encode($comments);
?>
