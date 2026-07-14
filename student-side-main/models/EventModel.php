
<?php

class EventModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getInterestedEvents($userId)
    {
        $sql = "
        SELECT
            e.EVENT_ID,
            e.TITLE,
            e.CATEGORY,
            e.DATE,
            e.BANNER_IMAGE
        FROM event_interest ei
        INNER JOIN event e
            ON ei.EVENT_ID=e.EVENT_ID
        WHERE ei.USER_ID=?
        ORDER BY e.DATE ASC";

        $stmt = mysqli_prepare($this->conn,$sql);

        mysqli_stmt_bind_param($stmt,"i",$userId);

        mysqli_stmt_execute($stmt);

        $result=mysqli_stmt_get_result($stmt);

        $events=[];

        while($row=mysqli_fetch_assoc($result))
        {
            $events[]=[
                "id"=>$row["EVENT_ID"],
                "title"=>$row["TITLE"],
                "category"=>$row["CATEGORY"],
                "date"=>$row["DATE"],
                "image"=>$row["BANNER_IMAGE"]
            ];
        }

        return $events;
    }

    public function getCategoryStats()
    {
        $sql="
        SELECT
            CATEGORY,
            COUNT(*) total
        FROM event
        WHERE APPROVAL_STATUS='APPROVED'
        GROUP BY CATEGORY";

        $result=mysqli_query($this->conn,$sql);

        $data=[];

        while($row=mysqli_fetch_assoc($result))
            $data[]=$row;

        return $data;
    }

    public function getPopularEvents()
    {
        $sql="
        SELECT
            e.TITLE,
            COUNT(ei.EVENT_ID) interested
        FROM event e
        LEFT JOIN event_interest ei
            ON e.EVENT_ID=ei.EVENT_ID
        WHERE e.APPROVAL_STATUS='APPROVED'
        GROUP BY e.EVENT_ID
        ORDER BY interested DESC
        LIMIT 5";

        $result=mysqli_query($this->conn,$sql);

        $data=[];

        while($row=mysqli_fetch_assoc($result))
            $data[]=$row;

        return $data;
    }
}
