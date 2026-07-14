<?php
require_once __DIR__ . '/Database.php';

/**
 * EventModel
 *
 * Encapsulates all SQL for the `event` table needed by the admin
 * "Manage Events" dashboard and the event review screen.
 */
class EventModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    /**
     * @param array $filters keys: search, date, category, status
     * @return array
     */
    public function getAllEvents($filters = []) {
        $sql = "SELECT e.EVENT_ID, e.TITLE, e.CATEGORY, e.VENUE, e.DATE, e.START_TIME,
                       e.END_TIME, e.APPROVAL_STATUS, e.STATUS, e.REMARKS, e.USER_ID,
                       u.USER_NAME AS officer_name
                FROM event e
                LEFT JOIN users u ON e.USER_ID = u.USER_ID
                WHERE 1 = 1";

        $params = [];
        $types = "";

        if (!empty($filters['search'])) {
            $sql .= " AND e.TITLE LIKE ?";
            $params[] = "%" . $filters['search'] . "%";
            $types .= "s";
        }

        if (!empty($filters['date'])) {
            $sql .= " AND e.DATE = ?";
            $params[] = $filters['date'];
            $types .= "s";
        }

        if (!empty($filters['category'])) {
            $sql .= " AND e.CATEGORY = ?";
            $params[] = strtoupper($filters['category']);
            $types .= "s";
        }

        if (!empty($filters['status'])) {
            $sql .= " AND e.APPROVAL_STATUS = ?";
            $params[] = strtoupper($filters['status']);
            $types .= "s";
        }

        $sql .= " ORDER BY e.DATE DESC, e.START_TIME DESC";

        $stmt = mysqli_prepare($this->conn, $sql);

        if ($types !== "") {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function getEventById($eventId) {
        $stmt = mysqli_prepare($this->conn, "SELECT e.*, u.USER_NAME AS officer_name
            FROM event e
            LEFT JOIN users u ON e.USER_ID = u.USER_ID
            WHERE e.EVENT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $eventId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result) ?: null;
    }

    /** @return array{PENDING:int, APPROVED:int, REJECTED:int} */
    public function getApprovalStatusCounts() {
        $counts = ['PENDING' => 0, 'APPROVED' => 0, 'REJECTED' => 0];

        $result = mysqli_query($this->conn, "SELECT APPROVAL_STATUS, COUNT(*) AS total 
                                             FROM event 
                                             GROUP BY APPROVAL_STATUS");

        while ($row = mysqli_fetch_assoc($result)) {
            if (isset($counts[$row['APPROVAL_STATUS']])) {
                $counts[$row['APPROVAL_STATUS']] = (int) $row['total'];
            }
        }

        return $counts;
    }

    public function updateApprovalStatus($eventId, $approvalStatus, $remarks) {
        $stmt = mysqli_prepare($this->conn, "UPDATE event
            SET APPROVAL_STATUS = ?, REMARKS = ?, UPDATED_AT = NOW()
            WHERE EVENT_ID = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $approvalStatus, $remarks, $eventId);

        return mysqli_stmt_execute($stmt);
    }
}