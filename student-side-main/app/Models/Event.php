<?php

require_once __DIR__ . "/../Core/Database.php";

/**
 * All queries against `event` / `event_interest` live here.
 */
class Event
{
    public static function categoryStats(): array
    {
        $result = Database::query("
            SELECT CATEGORY, COUNT(*) AS total
            FROM event
            WHERE APPROVAL_STATUS = 'APPROVED'
            GROUP BY CATEGORY
        ");

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function popular(int $limit = 5): array
    {
        // LIMIT can't be a bound param in all mysqli setups reliably as a
        // string type, so we cast to int ourselves to stay injection-safe.
        $limit = (int) $limit;

        $result = Database::query("
            SELECT e.TITLE, COUNT(ei.EVENT_ID) AS interested
            FROM event e
            LEFT JOIN event_interest ei ON e.EVENT_ID = ei.EVENT_ID
            WHERE e.APPROVAL_STATUS = 'APPROVED'
            GROUP BY e.EVENT_ID
            ORDER BY interested DESC
            LIMIT {$limit}
        ");

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public static function interestedByUser(int $userId): array
    {
        $result = Database::query("
            SELECT e.EVENT_ID, e.TITLE, e.CATEGORY, e.DATE, e.BANNER_IMAGE
            FROM event_interest ei
            INNER JOIN event e ON ei.EVENT_ID = e.EVENT_ID
            WHERE ei.USER_ID = ?
            ORDER BY e.DATE ASC
        ", "i", [$userId]);

        $events = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = [
                "id" => $row["EVENT_ID"],
                "title" => $row["TITLE"],
                "category" => $row["CATEGORY"],
                "date" => $row["DATE"],
                "image" => $row["BANNER_IMAGE"],
            ];
        }

        return $events;
    }
}
