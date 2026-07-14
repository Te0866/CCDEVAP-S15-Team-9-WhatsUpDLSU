<?php
require_once __DIR__ . "/Controller.php";

class CalendarController extends Controller {
    
    public function index() {
        $this->requireLogin();
        
        require_once __DIR__ . '/../profile-picture.php';
        
        $this->render('calendar', [
            'profilePath' => $profilePath ?? 'img/default-profile.png',
            'activeTab' => 'calendar'
        ]);
    }
}
