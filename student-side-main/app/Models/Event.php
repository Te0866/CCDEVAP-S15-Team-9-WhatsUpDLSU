<?php

require_once __DIR__ . "/../Core/Database.php";

class Event
{
    private const STATUS_EXPR = "CASE
        WHEN TIMESTAMP(e.DATE, e.END_TIME) <= NOW() THEN 'ENDED'
        WHEN TIMESTAMP(e.DATE, e.START_TIME) <= NOW() THEN 'ONGOING'
        ELSE 'UPCOMING'
    END";

    public static function categoryStats(): array
{
    $result = Database::query("
        SELECT CATEGORY, COUNT(*) AS total
        FROM event e
        WHERE APPROVAL_STATUS = 'APPROVED'
        AND (" . self::STATUS_EXPR . ") != 'ENDED'
        GROUP BY CATEGORY
    ");

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
    
    
public static function interestedCategoryStats(int $userId): array
{
    $result = Database::query("
        SELECT
            e.CATEGORY,
            COUNT(*) AS total
        FROM event_interest ei
        INNER JOIN event e
            ON ei.EVENT_ID = e.EVENT_ID
        WHERE ei.USER_ID = ?
          AND e.APPROVAL_STATUS = 'APPROVED'
          AND (" . self::STATUS_EXPR . ") != 'ENDED'
        GROUP BY e.CATEGORY
    ", "i", [$userId]);

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
    
public static function popular(int $limit = 5): array
{
    $limit = (int) $limit;

    $result = Database::query("
        SELECT e.TITLE, COUNT(ei.EVENT_ID) AS interested
        FROM event e
        LEFT JOIN event_interest ei ON e.EVENT_ID = ei.EVENT_ID
        WHERE e.APPROVAL_STATUS = 'APPROVED'
        AND (" . self::STATUS_EXPR . ") != 'ENDED'
        GROUP BY e.EVENT_ID
        ORDER BY interested DESC
        LIMIT {$limit}
    ");

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
   
    private static function bannerImageUrl(?string $filename): ?string
    {
        if ($filename === null) {
            return null;
        }

        $filename = trim($filename);
        if ($filename === '') {
            return null;
        }

        // If a full relative path was stored, use basename only
        if (strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            $filename = basename(str_replace('\\', '/', $filename));
        }

        // Public URL relative to student-side pages 
        return "../org-side-main/uploads/" . str_replace("%2F", "/", rawurlencode($filename));
    }

    public static function interestedByUser(int $userId): array
    {
        $result = Database::query("
    SELECT
        e.EVENT_ID,
        e.TITLE,
        e.CATEGORY,
        e.DATE,
        e.BANNER_IMAGE
    FROM event_interest ei
    INNER JOIN event e
        ON ei.EVENT_ID = e.EVENT_ID
    WHERE ei.USER_ID = ?
      AND e.APPROVAL_STATUS = 'APPROVED'
      AND (" . self::STATUS_EXPR . ") != 'ENDED'
    ORDER BY e.DATE ASC, e.START_TIME ASC
", "i", [$userId]);

        $events = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $image = null;
            if (!empty($row["BANNER_IMAGE"])) {
                $first = trim(explode(",", $row["BANNER_IMAGE"])[0]);
                $image = self::bannerImageUrl($first);
            }
            $events[] = [
                "id" => $row["EVENT_ID"],
                "title" => $row["TITLE"],
                "category" => $row["CATEGORY"],
                "date" => $row["DATE"],
                "image" => $image,
            ];
        }

        return $events;
    }

    public static function allApproved(?int $userId = null): array
    {
        $result = Database::query("
            SELECT
                e.EVENT_ID,
                e.TITLE,
                e.CATEGORY,
                e.DESCRIPTION,
                e.LOCATION,
                e.VENUE,
                e.DATE,
                e.START_TIME,
                e.END_TIME,
                " . self::STATUS_EXPR . " AS STATUS,
                e.REGISTRATION_STATUS,
                e.BANNER_IMAGE,
                u.USER_NAME,
                ei.INTEREST_ID
            FROM event e
            JOIN users u ON e.USER_ID = u.USER_ID
            LEFT JOIN event_interest ei
                ON ei.EVENT_ID = e.EVENT_ID AND ei.USER_ID = ?
            WHERE e.APPROVAL_STATUS = 'APPROVED'
            ORDER BY e.DATE ASC, e.START_TIME ASC
        ", "i", [$userId ?? 0]);

        $events = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $images = [];
            if (!empty($row["BANNER_IMAGE"])) {
                foreach (explode(",", $row["BANNER_IMAGE"]) as $filename) {
                    $filename = trim($filename);
                    if ($filename === "") {
                        continue;
                    }
                    $imageUrl = self::bannerImageUrl($filename);
                    if ($imageUrl !== null) {
                        $images[] = $imageUrl;
                    }
                }
            }

            $events[] = [
                "id" => (int) $row["EVENT_ID"],
                "isInterested" => $row["INTEREST_ID"] !== null,
                "title" => $row["TITLE"],
                "category" => $row["CATEGORY"],
                "description" => $row["DESCRIPTION"],
                "venue" => $row["VENUE"],
                "location" => $row["LOCATION"],
                "date" => $row["DATE"],
                "startTime" => $row["START_TIME"],
                "endTime" => $row["END_TIME"],
                "status" => $row["STATUS"],
                "registration" => $row["REGISTRATION_STATUS"] ? "Open" : "Closed",
                "organizer" => $row["USER_NAME"],
                "images" => $images,
                "comments" => [],
            ];
        }

        return $events;
    }

    public static function markInterested(int $userId, int $eventId): array
    {
        if ($eventId <= 0) {
            return [false, "Invalid event.", false];
        }

        $check = Database::query(
            "SELECT INTEREST_ID FROM event_interest WHERE USER_ID = ? AND EVENT_ID = ?",
            "ii",
            [$userId, $eventId]
        );

        $conn = Database::connection();

        if (mysqli_num_rows($check) > 0) {
            $stmt = mysqli_prepare($conn, "DELETE FROM event_interest WHERE USER_ID = ? AND EVENT_ID = ?");
            mysqli_stmt_bind_param($stmt, "ii", $userId, $eventId);
            return mysqli_stmt_execute($stmt)
                ? [true, "Removed from Interested Events.", false]
                : [false, mysqli_error($conn), true];
        }

        $stmt = mysqli_prepare($conn, "INSERT INTO event_interest(USER_ID, EVENT_ID) VALUES (?, ?)");

        if (!$stmt) {
            return [false, mysqli_error($conn), false];
        }

        mysqli_stmt_bind_param($stmt, "ii", $userId, $eventId);

        if (!mysqli_stmt_execute($stmt)) {
            return [false, mysqli_stmt_error($stmt), false];
        }

        return [true, "Added to Interested Events!", true];
    }
}
