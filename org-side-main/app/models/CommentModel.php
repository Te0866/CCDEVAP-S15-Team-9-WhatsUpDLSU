<?php
class CommentModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function forEventOwnedByUser($eventId, $userId)
    {
        $ownerCheck = mysqli_prepare($this->conn, "SELECT EVENT_ID FROM event WHERE EVENT_ID = ? AND USER_ID = ?");
        mysqli_stmt_bind_param($ownerCheck, "ii", $eventId, $userId);
        mysqli_stmt_execute($ownerCheck);
        if (!mysqli_fetch_assoc(mysqli_stmt_get_result($ownerCheck))) {
            return false;
        }

        $stmt = mysqli_prepare($this->conn, "SELECT COMMENT_ID, USERNAME, TEXT, IS_ANONYMOUS FROM comments WHERE EVENT_ID = ? ORDER BY COMMENT_ID DESC");
        mysqli_stmt_bind_param($stmt, "i", $eventId);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }

    public function deleteForOfficer($commentId, $userId)
    {
        $stmt = mysqli_prepare($this->conn, "DELETE c FROM comments c
            INNER JOIN event e ON e.EVENT_ID = c.EVENT_ID
            WHERE c.COMMENT_ID = ? AND e.USER_ID = ?");
        mysqli_stmt_bind_param($stmt, "ii", $commentId, $userId);

        $success = mysqli_stmt_execute($stmt);

        if (!$success) {
            return false;
        }

        if (mysqli_stmt_affected_rows($stmt) === 0) {
            return 'no_match';
        }

        return true;
    }

    public function lastError()
    {
        return mysqli_error($this->conn);
    }
}
