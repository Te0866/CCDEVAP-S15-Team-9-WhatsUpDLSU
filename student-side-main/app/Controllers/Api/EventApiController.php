<?php

require_once __DIR__ . "/../../Models/Event.php";

/**
 * Groups the three small JSON endpoints that used to be separate,
 * near-identical PHP files (get-category-stats.php, get-popular-events.php,
 * get-interested-events.php).
 */
class EventApiController
{
    public function categoryStats(): void
    {
        header("Content-Type: application/json");
        echo json_encode(Event::categoryStats());
    }

    public function popular(): void
    {
        header("Content-Type: application/json");
        echo json_encode(Event::popular(5));
    }

    public function interested(): void
    {
        header("Content-Type: application/json");
        session_start();

        if (!isset($_SESSION['user_id'])) {
            echo json_encode([]);
            return;
        }

        echo json_encode(Event::interestedByUser($_SESSION['user_id']));
    }
}
