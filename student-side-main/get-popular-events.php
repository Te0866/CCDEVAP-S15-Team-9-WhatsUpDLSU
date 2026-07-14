<?php
require_once __DIR__ . "/../dbconnection.php";

header("Content-Type: application/json");

$sql = "
SELECT
    e.TITLE,
    COUNT(i.EVENT_ID) AS interested
FROM event e
LEFT JOIN interested_events i
ON e.EVENT_ID = i.EVENT_ID
WHERE e.APPROVAL_STATUS = 'APPROVED'
GROUP BY e.EVENT_ID
ORDER BY interested DESC
LIMIT 5
";

$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
