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

        $approvalStatus = 'PENDING';

        $stmt = mysqli_prepare($this->conn, "UPDATE event SET
            CATEGORY = ?, TITLE = ?, DESCRIPTION = ?, LOCATION = ?, VENUE = ?,
            DATE = ?, START_TIME = ?, END_TIME = ?, STATUS = ?, BANNER_IMAGE = ?,
            APPROVAL_STATUS = ?, REMARKS = NULL, UPDATED_AT = NOW()
            WHERE EVENT_ID = ? AND USER_ID = ?");

        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssii",
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
            $approvalStatus,
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
        $sql = "SELECT EVENT_ID, TITLE, DATE, LOCATION, VENUE, CATEGORY, APPROVAL_STATUS, REMARKS FROM event WHERE USER_ID = ? ORDER BY UPDATED_AT DESC";
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

    public function totalCount($userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
    }

    public function rejectedCount($userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) AS total FROM event WHERE USER_ID = ? AND APPROVAL_STATUS = 'REJECTED'");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
    }

    public function locationCounts($userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT LOCATION, COUNT(*) AS total FROM event WHERE USER_ID = ? GROUP BY LOCATION ORDER BY total DESC");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $locations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $locations[$row['LOCATION']] = (int) $row['total'];
        }

        return $locations;
    }

    public function totalInterestCount($userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) AS total FROM event_interest ei
            INNER JOIN event e ON e.EVENT_ID = ei.EVENT_ID
            WHERE e.USER_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return (int) mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'];
    }

    public function topInterestedEvents($userId, $limit = 5)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT e.EVENT_ID, e.TITLE, COUNT(ei.INTEREST_ID) AS interest_total
            FROM event e
            LEFT JOIN event_interest ei ON ei.EVENT_ID = e.EVENT_ID
            WHERE e.USER_ID = ?
            GROUP BY e.EVENT_ID, e.TITLE
            ORDER BY interest_total DESC, e.TITLE ASC
            LIMIT " . (int) $limit);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function needsAttention($userId)
    {
        $items = [];

        $stmt = mysqli_prepare($this->conn, "SELECT EVENT_ID, TITLE, DATE FROM event
            WHERE USER_ID = ? AND APPROVAL_STATUS = 'REJECTED'
            ORDER BY UPDATED_AT DESC LIMIT 5");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = [
                'event_id' => $row['EVENT_ID'],
                'title' => $row['TITLE'],
                'type' => 'REJECTED',
                'message' => 'was rejected and needs edits before it can be resubmitted'
            ];
        }

        $stmt = mysqli_prepare($this->conn, "SELECT EVENT_ID, TITLE, DATE FROM event
            WHERE USER_ID = ? AND APPROVAL_STATUS = 'PENDING'
            AND DATE BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 5 DAY)
            ORDER BY DATE ASC LIMIT 5");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $daysUntil = (int) floor((strtotime($row['DATE']) - strtotime(date('Y-m-d'))) / 86400);
            $whenText = $daysUntil <= 0 ? 'today' : ($daysUntil === 1 ? 'in 1 day' : "in {$daysUntil} days");
            $items[] = [
                'event_id' => $row['EVENT_ID'],
                'title' => $row['TITLE'],
                'type' => 'PENDING',
                'message' => "is happening {$whenText} but is still awaiting admin approval"
            ];
        }

        return $items;
    }

    public function categoryApprovalBreakdown($userId)
    {
        $breakdown = [
            'ACADEMIC' => ['APPROVED' => 0, 'PENDING' => 0, 'REJECTED' => 0],
            'NON-ACADEMIC' => ['APPROVED' => 0, 'PENDING' => 0, 'REJECTED' => 0],
            'CAREER' => ['APPROVED' => 0, 'PENDING' => 0, 'REJECTED' => 0],
        ];

        $stmt = mysqli_prepare($this->conn, "SELECT CATEGORY, APPROVAL_STATUS, COUNT(*) AS total
            FROM event WHERE USER_ID = ? GROUP BY CATEGORY, APPROVAL_STATUS");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            if (isset($breakdown[$row['CATEGORY']][$row['APPROVAL_STATUS']])) {
                $breakdown[$row['CATEGORY']][$row['APPROVAL_STATUS']] = (int) $row['total'];
            }
        }

        return $breakdown;
    }

    public function eventInterestScatter($userId)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT e.TITLE, e.DATE, e.CATEGORY, COUNT(ei.INTEREST_ID) AS interest_total
            FROM event e
            LEFT JOIN event_interest ei ON ei.EVENT_ID = e.EVENT_ID
            WHERE e.USER_ID = ?
            GROUP BY e.EVENT_ID, e.TITLE, e.DATE, e.CATEGORY
            ORDER BY e.DATE ASC");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $points = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $points[] = [
                'title' => $row['TITLE'],
                'date' => date("M j", strtotime($row['DATE'])),
                'category' => $row['CATEGORY'],
                'interest' => (int) $row['interest_total']
            ];
        }

        return $points;
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
