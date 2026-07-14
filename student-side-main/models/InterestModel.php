<?php
class InterestModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getUserInterestedEvents($userId) {
        $sql = "SELECT e.EVENT_ID, e.TITLE, e.CATEGORY, e.DATE, e.BANNER_IMAGE 
                FROM event_interest ei 
                INNER JOIN event e ON ei.EVENT_ID = e.EVENT_ID 
                WHERE ei.USER_ID = ? 
                ORDER BY e.DATE ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function addInterest($userId, $eventId) {
        $sql = "INSERT INTO event_interest (USER_ID, EVENT_ID) VALUES (?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $eventId);
        return mysqli_stmt_execute($stmt);
    }
    
    public function removeInterest($userId, $eventId) {
        $sql = "DELETE FROM event_interest WHERE USER_ID = ? AND EVENT_ID = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $eventId);
        return mysqli_stmt_execute($stmt);
    }
    
    public function isUserInterested($userId, $eventId) {
        $sql = "SELECT * FROM event_interest WHERE USER_ID = ? AND EVENT_ID = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $userId, $eventId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }
}
