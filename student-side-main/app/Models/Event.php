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

    /**
     * All approved events with full detail, for the events page.
     * Equivalent to the old get-events.php.
     */
    public static function allApproved(): array
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
            e.STATUS,
            e.REGISTRATION_STATUS,
            e.BANNER_IMAGE,
            u.USER_NAME
        FROM event e
        JOIN users u ON e.USER_ID = u.USER_ID
        WHERE e.APPROVAL_STATUS = 'APPROVED'
        ORDER BY e.DATE ASC, e.START_TIME ASC
    ");

    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $images = [];
        if (!empty($row["BANNER_IMAGE"])) {
            $images[] = $row["BANNER_IMAGE"];
        }

        $events[] = [
            "id" => (int) $row["EVENT_ID"],
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
    /**
     * Records that a user is interested in an event. Returns
     * [success, message] so the controller can turn it straight into JSON.
     * Equivalent to the old add-interest.php.
     */
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
    mysqli_stmt_bind_param($stmt, "ii", $userId, $eventId);
    return mysqli_stmt_execute($stmt)
        ? [true, "Added to Interested Events!", true]
        : [false, mysqli_error($conn), false];
}
}
