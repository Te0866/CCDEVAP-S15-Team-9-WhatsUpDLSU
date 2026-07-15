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
            FROM event
            WHERE APPROVAL_STATUS = 'APPROVED'
            GROUP BY CATEGORY
        ");

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
            GROUP BY e.EVENT_ID
            ORDER BY interested DESC
            LIMIT {$limit}
        ");

        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    private static function bannerImageUrl(?string $filename): ?string
    {
        if (empty($filename)) {
            return null;
        }

        return "../org-side-main/uploads/{$filename}";
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
                "image" => self::bannerImageUrl($row["BANNER_IMAGE"]),
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
            $imageUrl = self::bannerImageUrl($row["BANNER_IMAGE"]);
            if ($imageUrl !== null) {
                $images[] = $imageUrl;
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
            // Already interested — always allow removing, even if the
            // event has since ended, so stale interest can be cleaned up.
            $stmt = mysqli_prepare($conn, "DELETE FROM event_interest WHERE USER_ID = ? AND EVENT_ID = ?");
            mysqli_stmt_bind_param($stmt, "ii", $userId, $eventId);
            return mysqli_stmt_execute($stmt)
                ? [true, "Removed from Interested Events.", false]
                : [false, mysqli_error($conn), true];
        }

        // Not yet interested — trying to add. Block it if the event has
        // already ended.
        $statusResult = Database::query(
            "SELECT STATUS FROM event WHERE EVENT_ID = ?",
            "i",
            [$eventId]
        );
        $eventRow = mysqli_fetch_assoc($statusResult);

        if (!$eventRow) {
            return [false, "Event not found.", false];
        }

        if ($eventRow["STATUS"] === "ENDED") {
            return [false, "This event has already ended.", false];
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
