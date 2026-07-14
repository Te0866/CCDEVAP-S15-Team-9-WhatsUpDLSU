<?php
require_once __DIR__ . "/../dbconnection.php";

header("Content-Type: application/json");

$sql = "
SELECT
    e.TITLE,
    COUNT(ei.EVENT_ID) AS interested
FROM event e
LEFT JOIN event_interest ei
    ON e.EVENT_ID = ei.EVENT_ID
WHERE e.APPROVAL_STATUS = 'APPROVED'
GROUP BY e.EVENT_ID
ORDER BY interested DESC
LIMIT 5
";

$result = mysqli_query($conn, $sql);

$data = [];

while($row = mysqli_fetch_assoc($result)){
    $data[] = $row;
}

echo json_encode($data);
