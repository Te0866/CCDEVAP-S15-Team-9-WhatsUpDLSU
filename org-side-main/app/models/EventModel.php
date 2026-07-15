<?php
class EventModel
{
    private $conn;

    private const STATUS_EXPR = "CASE
        WHEN TIMESTAMP(DATE, END_TIME) <= NOW() THEN 'ENDED'
        WHEN TIMESTAMP(DATE, START_TIME) <= NOW() THEN 'ONGOING'
        ELSE 'UPCOMING'
    END";

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    private function computeStatus($eventDate, $startTime, $endTime)
    {
        $now = time();
        $eventEnd = strtotime($eventDate . ' ' . $endTime);
        $eventStart = strtotime($eventDate . ' ' . $startTime);

        if ($eventEnd <= $now) {
            return 'ENDED';
        } else if ($eventStart <= $now) {
            return 'ONGOING';
        } else {
            return 'UPCOMING';
        }
    }

    public function create($userId, $data)
    {
        $status = $this->computeStatus($data['eventDate'], $data['startTime'], $data['endTime']);

        $approvalStatus = 'PENDING';
        $registrationStatus = 1;
        $category = strtoupper($data['category']);

        $stmt = mysqli_prepare($this->conn, "INSERT INTO event
            (USER_ID, CATEGORY, TITLE, DESCRIPTION, LOCATION, VENUE, DATE, START_TIME, END_TIME, APPROVAL_STATUS, STATUS, REGISTRATION_STATUS, BANNER_IMAGE, CREATED_AT, UPDATED_AT)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");

        mysqli_stmt_bind_param(
            $stmt,
            "issssssssssis",
            $userId,
            $category,
            $data['eventName'],
            $data['description'],
            $data['location'],
            $data['room'],
            $data['eventDate'],
            $data['startTime'],
            $data['endTime'],
            $approvalStatus,
            $status,
            $registrationStatus,
            $data['bannerImage']
        );

        return mysqli_stmt_execute($stmt);
    }

    public function update($eventId, $userId, $data)
    {
        $status = $this->computeStatus($data['eventDate'], $data['startTime'], $data['endTime']);

        $category = strtoupper($data['category']);

        $stmt = mysqli_prepare($this->conn, "UPDATE event SET
            CATEGORY = ?, TITLE = ?, DESCRIPTION = ?, LOCATION = ?, VENUE = ?,
            DATE = ?, START_TIME = ?, END_TIME = ?, STATUS = ?, BANNER_IMAGE = ?, UPDATED_AT = NOW()
            WHERE EVENT_ID = ? AND USER_ID = ?");

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssii",
            $category,
            $data['eventName'],
            $data['description'],
            $data['location'],
            $data['room'],
            $data['eventDate'],
            $data['startTime'],
            $data['endTime'],
            $status,
            $data['bannerImage'],
            $eventId,
            $userId
        );

        $success = mysqli_stmt_execute($stmt);

        if (!$success) {
            return false;
        }

        if (mysqli_stmt_affected_rows($stmt) === 0) {
            return 'no_match';
        }

        return true;
    }

    public function delete($eventId, $userId)
    {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM event WHERE EVENT_ID = ? AND USER_ID = ?");
        mysqli_stmt_bind_param($stmt, "ii", $eventId, $userId);

        $success = mysqli_stmt_execute($stmt);

        if (!$success) {
            return false;
        }

        if (mysqli_stmt_affected_rows($stmt) === 0) {
            return 'no_match';
        }

        return true;
    }

    public function findByIdForUser($eventId, $userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM event WHERE EVENT_ID = ? AND USER_ID = ?");
        mysqli_stmt_bind_param($stmt, "ii", $eventId, $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }

    public function allForUser($userId)
    {
        $sql = "SELECT EVENT_ID, TITLE, DATE, LOCATION, VENUE, CATEGORY, APPROVAL_STATUS FROM event WHERE USER_ID = ? ORDER BY DATE DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function approvalStatusCounts($userId)
    {
        $counts = ['PENDING' => 0, 'APPROVED' => 0, 'REJECTED' => 0];

        $stmt = mysqli_prepare($this->conn, "SELECT APPROVAL_STATUS, COUNT(*) AS total FROM event WHERE USER_ID = ? GROUP BY APPROVAL_STATUS");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $countResult = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($countResult)) {
            if (array_key_exists($row['APPROVAL_STATUS'], $counts)) {
                $counts[$row['APPROVAL_STATUS']] = $row['total'];
            }
        }

        return $counts;
    }

    public function activeCount($userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND (" . self::STATUS_EXPR . ") IN ('ONGOING', 'UPCOMING') AND APPROVAL_STATUS = 'APPROVED'");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
    }

    public function pendingCount($userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND APPROVAL_STATUS = 'PENDING'");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
    }

    public function pastCount($userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND (" . self::STATUS_EXPR . ") = 'ENDED'");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
    }

    public function upcomingForUser($userId, $limit = 10)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT EVENT_ID, TITLE, DATE, CATEGORY FROM event WHERE USER_ID = ? AND APPROVAL_STATUS = 'APPROVED' AND (" . self::STATUS_EXPR . ") IN ('UPCOMING', 'ONGOING') ORDER BY DATE ASC LIMIT " . (int) $limit);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function recentActivity($userId, $limit = 5)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT EVENT_ID, TITLE, APPROVAL_STATUS, REMARKS, UPDATED_AT FROM event WHERE USER_ID = ? ORDER BY UPDATED_AT DESC LIMIT " . (int) $limit);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function countByCategory($userId, $category)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND CATEGORY = ?");
        mysqli_stmt_bind_param($stmt, "is", $userId, $category);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
    }

    public function lastError()
    {
        return mysqli_error($this->conn);
    }
}
