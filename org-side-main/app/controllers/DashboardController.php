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

        $totalCount = $this->eventModel->totalCount($userId);
        $activeCount = $this->eventModel->activeCount($userId);
        $pendingCount = $this->eventModel->pendingCount($userId);
        $rejectedCount = $this->eventModel->rejectedCount($userId);

        $eventsResult = $this->eventModel->upcomingForUser($userId, 10);
        $activityResult = $this->eventModel->recentActivity($userId, 5);

        $academicCount = $this->eventModel->countByCategory($userId, 'ACADEMIC');
        $nonAcademicCount = $this->eventModel->countByCategory($userId, 'NON-ACADEMIC');
        $careerCount = $this->eventModel->countByCategory($userId, 'CAREER');

        $locationCounts = $this->eventModel->locationCounts($userId);
        $totalInterestCount = $this->eventModel->totalInterestCount($userId);
        $topInterestedResult = $this->eventModel->topInterestedEvents($userId, 5);

        $categoryTotals = [
            'Academic' => $academicCount,
            'Non-Academic' => $nonAcademicCount,
            'Career' => $careerCount
        ];
        arsort($categoryTotals);
        $topCategoryLabel = array_key_first($categoryTotals);
        $topCategoryCount = $categoryTotals[$topCategoryLabel];

        $topLocationLabel = $locationCounts === [] ? null : array_key_first($locationCounts);
        $topLocationCount = $topLocationLabel !== null ? $locationCounts[$topLocationLabel] : 0;

        require __DIR__ . "/../views/dashboard.view.php";
    }
}
