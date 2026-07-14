<?php
require_once __DIR__ . "/Controller.php";
require_once __DIR__ . "/../models/EventModel.php";
require_once __DIR__ . "/../models/InterestModel.php";
require_once __DIR__ . "/../models/UserModel.php";

class DashboardController extends Controller {
    
    public function index() {
        $this->requireLogin();
        
        $userId = $_SESSION['user_id'];
        
        $userModel = new UserModel();
        $user = $userModel->getUserById($userId);
        
        $eventModel = new EventModel();
        $categoryStats = $eventModel->getCategoryStats();
        $popularEvents = $eventModel->getPopularEvents();
        
        $interestModel = new InterestModel();
        $interestedEvents = $interestModel->getUserInterestedEvents($userId);
        
        require_once __DIR__ . '/../profile-picture.php';
        
        $this->render('dashboard', [
            'user' => $user,
            'categoryStats' => $categoryStats,
            'popularEvents' => $popularEvents,
            'interestedEvents' => $interestedEvents,
            'profilePath' => $profilePath ?? 'img/default-profile.png',
            'activeTab' => 'home'
        ]);
    }
    
    public function getInterestedEvents() {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $interestModel = new InterestModel();
        $events = $interestModel->getUserInterestedEvents($userId);
        
        $formatted = array_map(function($e) {
            return [
                'id' => $e['EVENT_ID'],
                'title' => $e['TITLE'],
                'category' => $e['CATEGORY'],
                'date' => $e['DATE']
            ];
        }, $events);
        
        $this->json($formatted);
    }
    
    public function getPopularEvents() {
        $eventModel = new EventModel();
        $this->json($eventModel->getPopularEvents());
    }
    
    public function getCategoryStats() {
        $eventModel = new EventModel();
        $this->json($eventModel->getCategoryStats());
    }
}
