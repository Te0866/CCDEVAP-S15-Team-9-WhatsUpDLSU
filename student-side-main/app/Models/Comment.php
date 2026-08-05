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
            "SELECT c.COMMENT_ID, c.EVENT_ID, c.TEXT, c.IS_ANONYMOUS, e.TITLE AS EVENT_TITLE
             FROM comments c
             JOIN event e ON e.EVENT_ID = c.EVENT_ID
             WHERE c.USERNAME = ?
             ORDER BY c.COMMENT_ID DESC",
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

