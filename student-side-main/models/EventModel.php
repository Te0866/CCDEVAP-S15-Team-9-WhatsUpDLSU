
<?php
class EventModel {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function getApprovedEvents() {
        $sql = "SELECT * FROM event WHERE APPROVAL_STATUS = 'APPROVED' ORDER BY DATE ASC";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getEventsByCategory($category) {
        $sql = "SELECT * FROM event WHERE CATEGORY = ? AND APPROVAL_STATUS = 'APPROVED' ORDER BY DATE ASC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $category);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getEventById($eventId) {
        $sql = "SELECT * FROM event WHERE EVENT_ID = ? AND APPROVAL_STATUS = 'APPROVED'";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $eventId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
    public function getPopularEvents($limit = 5) {
        $sql = "SELECT e.TITLE, e.EVENT_ID, COUNT(ei.EVENT_ID) AS interested 
                FROM event e 
                LEFT JOIN event_interest ei ON e.EVENT_ID = ei.EVENT_ID 
                WHERE e.APPROVAL_STATUS = 'APPROVED' 
                GROUP BY e.EVENT_ID 
                ORDER BY interested DESC 
                LIMIT $limit";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function getCategoryStats() {
        $sql = "SELECT CATEGORY, COUNT(*) AS total 
                FROM event 
                WHERE APPROVAL_STATUS = 'APPROVED' 
                GROUP BY CATEGORY";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
