<?php
class DashboardController
{
    private $conn;
    private $userModel;
    private $eventModel;

    public function __construct()
    {
        $this->conn = Database::connection();
        $this->userModel = new UserModel($this->conn);
        $this->eventModel = new EventModel($this->conn);
    }

    public function index()
    {
        Auth::requireOfficer();

        $userId = Auth::currentUserId();

        $user = $this->userModel->findById($userId);
        if (!$user) {
            die("User not found.");
        }

        $profilePath = ProfilePicture::resolve($this->conn, $user, $userId);

        $activeCount = $this->eventModel->activeCount($userId);
        $pendingCount = $this->eventModel->pendingCount($userId);
        $pastCount = $this->eventModel->pastCount($userId);

        $eventsResult = $this->eventModel->upcomingForUser($userId, 10);
        $activityResult = $this->eventModel->recentActivity($userId, 5);

        $academicCount = $this->eventModel->countByCategory($userId, 'ACADEMIC');
        $nonAcademicCount = $this->eventModel->countByCategory($userId, 'NON-ACADEMIC');
        $careerCount = $this->eventModel->countByCategory($userId, 'CAREER');

        require __DIR__ . "/../views/dashboard.view.php";
    }
}
