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

    /**
     * Equivalent to the old get-events.php — full detail for the events page.
     */
    public function all(): void
    {
        header("Content-Type: application/json");
        echo json_encode(Event::allApproved());
    }

    /**
     * Equivalent to the old add-interest.php.
     */
   public function addInterest(): void
{
    header("Content-Type: application/json");
    session_start();

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "Please log in."]);
        return;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $eventId = intval($data['event_id'] ?? 0);

    [$success, $message, $interested] = Event::markInterested($_SESSION['user_id'], $eventId);

    echo json_encode(["success" => $success, "message" => $message, "interested" => $interested]);
}
