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

        $categoryApprovalBreakdown = $this->eventModel->categoryApprovalBreakdown($userId);
        $scatterData = $this->eventModel->eventInterestScatter($userId);

        $locationCounts = $this->eventModel->locationCounts($userId);
        $totalInterestCount = $this->eventModel->totalInterestCount($userId);
        $topInterestedResult = $this->eventModel->topInterestedEvents($userId, 5);

        $attentionItems = $this->eventModel->needsAttention($userId);

        require __DIR__ . "/../views/dashboard.view.php";
    }
}
