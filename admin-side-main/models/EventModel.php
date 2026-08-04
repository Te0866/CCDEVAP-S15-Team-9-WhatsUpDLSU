<?php
require_once __DIR__ . '/Database.php';


class EventModel {
    private $conn;

    private const STATUS_EXPR = "CASE
        WHEN TIMESTAMP(e.DATE, e.END_TIME) <= NOW() THEN 'ENDED'
        WHEN TIMESTAMP(e.DATE, e.START_TIME) <= NOW() THEN 'ONGOING'
        ELSE 'UPCOMING'
    END";

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function getAllEvents($filters = []) {
        $sql = "SELECT e.EVENT_ID, e.TITLE, e.CATEGORY, e.VENUE, e.DATE, e.START_TIME,
                       e.END_TIME, e.APPROVAL_STATUS, " . self::STATUS_EXPR . " AS STATUS, e.REMARKS, e.USER_ID,
                       u.USER_NAME AS ORG_NAME
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

        $sql .= " ORDER BY e.CREATED_AT DESC, e.EVENT_ID DESC";

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
        $stmt = mysqli_prepare($this->conn, "SELECT e.*, u.USER_NAME AS ORG_NAME, " . self::STATUS_EXPR . " AS STATUS
            FROM event e
            LEFT JOIN users u ON e.USER_ID = u.USER_ID
            WHERE e.EVENT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $eventId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result) ?: null;
    }

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

    // Creates a new event (used by admin Add Event form)
    public function createEvent($data) {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO event
            (USER_ID, CATEGORY, TITLE, DESCRIPTION, LOCATION, VENUE, DATE, START_TIME, END_TIME,
             APPROVAL_STATUS, REMARKS, STATUS, REGISTRATION_STATUS, BANNER_IMAGE, CREATED_AT, UPDATED_AT)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'UPCOMING', ?, '', NOW(), NOW())");

        mysqli_stmt_bind_param(
            $stmt,
            "issssssssssi",
            $data['userId'],
            $data['category'],
            $data['title'],
            $data['description'],
            $data['location'],
            $data['venue'],
            $data['date'],
            $data['startTime'],
            $data['endTime'],
            $data['approvalStatus'],
            $data['remarks'],
            $data['registrationStatus']
        );

        if (!mysqli_stmt_execute($stmt)) {
            return 0;
        }

        return mysqli_insert_id($this->conn);
    }

    // Updates an existing event (used by admin Edit Event form)
    public function updateEvent($eventId, $data) {
        $stmt = mysqli_prepare($this->conn, "UPDATE event SET
            USER_ID = ?, CATEGORY = ?, TITLE = ?, DESCRIPTION = ?, LOCATION = ?, VENUE = ?,
            DATE = ?, START_TIME = ?, END_TIME = ?, APPROVAL_STATUS = ?, REMARKS = ?,
            REGISTRATION_STATUS = ?, UPDATED_AT = NOW()
            WHERE EVENT_ID = ?");

        mysqli_stmt_bind_param(
            $stmt,
            "issssssssssii",
            $data['userId'],
            $data['category'],
            $data['title'],
            $data['description'],
            $data['location'],
            $data['venue'],
            $data['date'],
            $data['startTime'],
            $data['endTime'],
            $data['approvalStatus'],
            $data['remarks'],
            $data['registrationStatus'],
            $eventId
        );

        return mysqli_stmt_execute($stmt);
    }

    // Deletes an event
    public function deleteEvent($eventId) {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM event WHERE EVENT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $eventId);
        return mysqli_stmt_execute($stmt);
    }

    // Returns organizer accounts (officers) for the Add/Edit Event form dropdown
    public function getOrganizers() {
        $result = mysqli_query($this->conn, "SELECT USER_ID, USER_NAME FROM users WHERE ROLE = 'OFFICER' ORDER BY USER_NAME ASC");

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }
}
