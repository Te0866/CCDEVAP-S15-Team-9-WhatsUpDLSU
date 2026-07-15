<?php
class EventController
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

    public function create()
    {
        Auth::requireOfficer();

        $userId = Auth::currentUserId();

        $user = $this->userModel->findById($userId);
        if (!$user) {
            die("User not found.");
        }

        $profilePath = ProfilePicture::resolve($this->conn, $user, $userId);

        require __DIR__ . "/../views/create.view.php";
    }

    public function store()
    {
        Auth::requireOfficer();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: create.php");
            exit;
        }

        $userId = Auth::currentUserId();

        $uploadDir = __DIR__ . "/../../uploads/";
        $bannerImage = ImageUploader::storeIfPresent($_FILES['event_image'] ?? null, $uploadDir);

        $data = [
            'eventName' => $_POST['event_name'],
            'category' => $_POST['category'],
            'location' => $_POST['location'],
            'room' => $_POST['room'],
            'eventDate' => $_POST['event_date'],
            'startTime' => $_POST['start_time'],
            'endTime' => $_POST['end_time'],
            'description' => $_POST['description'],
            'bannerImage' => $bannerImage !== null ? $bannerImage : '',
        ];

        $success = $this->eventModel->create($userId, $data);

        if ($success) {
            header("Location: officer-dashboard.php");
            exit;
        } else {
            die("Something went wrong: " . $this->eventModel->lastError());
        }
    }

    public function manage()
    {
        Auth::requireOfficer();

        $userId = Auth::currentUserId();

        $user = $this->userModel->findById($userId);
        if (!$user) {
            die("User not found.");
        }

        $profilePath = ProfilePicture::resolve($this->conn, $user, $userId);

        $eventsResult = $this->eventModel->allForUser($userId);
        $counts = $this->eventModel->approvalStatusCounts($userId);

        $pendingCount = $counts['PENDING'];
        $approvedCount = $counts['APPROVED'];
        $rejectedCount = $counts['REJECTED'];

        require __DIR__ . "/../views/manage.view.php";
    }

    public function edit()
    {
        Auth::requireOfficer();

        $userId = Auth::currentUserId();

        $user = $this->userModel->findById($userId);
        if (!$user) {
            die("User not found.");
        }

        $profilePath = ProfilePicture::resolve($this->conn, $user, $userId);

        if (!isset($_GET['event_id'])) {
            header("Location: manage.php");
            exit;
        }

        $eventId = $_GET['event_id'];

        $event = $this->eventModel->findByIdForUser($eventId, $userId);

        if (!$event) {
            die("Event not found or you don't have permission to edit it.");
        }

        require __DIR__ . "/../views/edit-event.view.php";
    }

    public function update()
    {
        Auth::requireOfficer();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: manage.php");
            exit;
        }

        $userId = Auth::currentUserId();
        $eventId = $_POST['event_id'];

        $bannerImage = $_POST['existing_image'];

        if (isset($_POST['remove_image']) && $_POST['remove_image'] === '1') {
            $bannerImage = '';
        }

        $uploadDir = __DIR__ . "/../../uploads/";
        $newBannerImage = ImageUploader::storeIfPresent($_FILES['event_image'] ?? null, $uploadDir);
        if ($newBannerImage !== null) {
            $bannerImage = $newBannerImage;
        }

        $data = [
            'eventName' => $_POST['event_name'],
            'category' => $_POST['category'],
            'location' => $_POST['location'],
            'room' => $_POST['room'],
            'eventDate' => $_POST['event_date'],
            'startTime' => $_POST['start_time'],
            'endTime' => $_POST['end_time'],
            'description' => $_POST['description'],
            'bannerImage' => $bannerImage,
        ];

        $result = $this->eventModel->update($eventId, $userId, $data);

        if ($result === false) {
            die("Update failed: " . $this->eventModel->lastError());
        }

        if ($result === 'no_match') {
            die("Update failed: no event matched this ID for your account. " .
                "Either the event doesn't exist, or it doesn't belong to your account.");
        }

        header("Location: manage.php");
        exit;
    }
}
