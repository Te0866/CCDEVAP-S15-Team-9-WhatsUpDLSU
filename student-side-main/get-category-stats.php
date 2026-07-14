<?php
require_once __DIR__ . "/../dbconnection.php";

header("Content-Type: application/json");

$sql = "
SELECT
    CATEGORY,
    COUNT(*) AS total
FROM event
WHERE APPROVAL_STATUS = 'APPROVED'
GROUP BY CATEGORY
";

$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
