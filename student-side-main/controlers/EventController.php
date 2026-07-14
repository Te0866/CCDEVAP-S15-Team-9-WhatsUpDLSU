<?php
require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/EventModel.php";
require_once __DIR__ . "/../models/InterestModel.php";

class EventController extends Controller {
    
    public function index() {
        $this->requireLogin();
        
        $category = $_GET['category'] ?? null;
        $eventModel = new EventModel();
        $events = $category ? $eventModel->getEventsByCategory($category) : $eventModel->getApprovedEvents();
        
        require_once __DIR__ . '/../profile-picture.php';
        
        $this->render('events', [
            'events' => $events,
            'category' => $category,
            'profilePath' => $profilePath ?? 'img/default-profile.png',
            'activeTab' => 'events'
        ]);
    }
    
    public function show($id) {
        $this->requireLogin();
        
        $eventModel = new EventModel();
        $event = $eventModel->getEventById($id);
        
        if (!$event) {
            header("Location: ?page=events");
            exit;
        }
        
        $interestModel = new InterestModel();
        $isInterested = $interestModel->isUserInterested($_SESSION['user_id'], $id);
        
        require_once __DIR__ . '/../profile-picture.php';
        
        $this->render('event-detail', [
            'event' => $event,
            'isInterested' => $isInterested,
            'profilePath' => $profilePath ?? 'img/default-profile.png',
            'activeTab' => 'events'
        ]);
    }
    
    public function toggleInterest() {
        $this->requireLogin();
        
        $eventId = $_POST['event_id'] ?? null;
        $userId = $_SESSION['user_id'];
        
        if (!$eventId) {
            $this->json(['success' => false, 'message' => 'Event ID required']);
        }
        
        $interestModel = new InterestModel();
        
        if ($interestModel->isUserInterested($userId, $eventId)) {
            $success = $interestModel->removeInterest($userId, $eventId);
            $action = 'removed';
        } else {
            $success = $interestModel->addInterest($userId, $eventId);
            $action = 'added';
        }
        
        $this->json(['success' => $success, 'action' => $action]);
    }
}
