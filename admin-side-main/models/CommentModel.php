<?php
require_once __DIR__ . '/Database.php';

class CommentModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    // Returns all comments, optionally filtered by search text/username and event
    public function getAllComments($search = null, $eventId = null) {
        $sql = "SELECT c.COMMENT_ID, c.USERNAME, c.TEXT, c.IS_ANONYMOUS, c.EVENT_ID,
                       e.TITLE AS EVENT_TITLE
                FROM comments c
                LEFT JOIN event e ON c.EVENT_ID = e.EVENT_ID
                WHERE 1 = 1";

        $params = [];
        $types = "";

        if ($search !== null && $search !== '') {
            $sql .= " AND (c.TEXT LIKE ? OR c.USERNAME LIKE ?)";
            $params[] = "%" . $search . "%";
            $params[] = "%" . $search . "%";
            $types .= "ss";
        }

        if ($eventId !== null && $eventId !== '') {
            $sql .= " AND c.EVENT_ID = ?";
            $params[] = (int) $eventId;
            $types .= "i";
        }

        $sql .= " ORDER BY c.COMMENT_ID DESC";

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

    public function getCommentById($commentId) {
        $stmt = mysqli_prepare($this->conn, "SELECT c.*, e.TITLE AS EVENT_TITLE
            FROM comments c
            LEFT JOIN event e ON c.EVENT_ID = e.EVENT_ID
            WHERE c.COMMENT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $commentId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result) ?: null;
    }

    // Returns events for the comment form dropdown
    public function getEventOptions() {
        $result = mysqli_query($this->conn, "SELECT EVENT_ID, TITLE FROM event ORDER BY DATE DESC, EVENT_ID DESC");

        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function createComment($eventId, $username, $text, $isAnonymous) {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO comments (EVENT_ID, USERNAME, TEXT, IS_ANONYMOUS) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issi", $eventId, $username, $text, $isAnonymous);

        if (!mysqli_stmt_execute($stmt)) {
            return 0;
        }

        return mysqli_insert_id($this->conn);
    }

    public function updateComment($commentId, $eventId, $username, $text, $isAnonymous) {
        $stmt = mysqli_prepare($this->conn, "UPDATE comments
            SET EVENT_ID = ?, USERNAME = ?, TEXT = ?, IS_ANONYMOUS = ?
            WHERE COMMENT_ID = ?");
        mysqli_stmt_bind_param($stmt, "issii", $eventId, $username, $text, $isAnonymous, $commentId);

        return mysqli_stmt_execute($stmt);
    }

    public function deleteComment($commentId) {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM comments WHERE COMMENT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $commentId);
        return mysqli_stmt_execute($stmt);
    }
}
