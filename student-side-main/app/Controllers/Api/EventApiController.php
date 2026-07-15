<?php

require_once __DIR__ . "/../../Models/Event.php";

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

    public function all(): void
    {
        header("Content-Type: application/json");
        session_start();

        $userId = $_SESSION['user_id'] ?? null;
        echo json_encode(Event::allApproved($userId));
    }

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

        [$success, $message, $isInterested] = Event::markInterested($_SESSION['user_id'], $eventId);

        echo json_encode([
            "success" => $success,
            "message" => $message,
            "interested" => $isInterested,
        ]);
    }
}
