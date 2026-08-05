<?php

require_once __DIR__ . "/../Core/Database.php";

class Comment
{
    /**
     * Returns every comment a given username has posted, most recent first.
     */
    public static function findByUsername(string $username): array
    {
        $result = Database::query(
            "SELECT COMMENT_ID, EVENT_ID, TEXT, IS_ANONYMOUS FROM comments WHERE USERNAME = ? ORDER BY COMMENT_ID DESC",
            "s",
            [$username]
        );

        $comments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $comments[] = $row;
        }
        return $comments;
    }
}
