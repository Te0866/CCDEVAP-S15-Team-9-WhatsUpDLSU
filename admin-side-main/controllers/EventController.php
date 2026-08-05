<?php
require_once __DIR__ . '/../models/EventModel.php';
require_once __DIR__ . '/../models/OrganizationModel.php';

class EventController {
    private $eventModel;
    private $orgModel;

    public function __construct() {
        $this->eventModel = new EventModel();
        $this->orgModel = new OrganizationModel();
    }

    public function getDashboardData($filters = []) {
        $events = $this->eventModel->getAllEvents($filters);
        $counts = $this->eventModel->getApprovalStatusCounts();
        $orgsCount = $this->orgModel->getTotalOrganizations();

        return [
            'events' => $events,
            'counts' => $counts,
            'orgsCount' => $orgsCount,
        ];
    }

    public function getEventForReview($eventId) {
        return $this->eventModel->getEventById($eventId);
    }

    public function reviewEvent($eventId, $action, $remarks) {
        $event = $this->eventModel->getEventById($eventId);

        if (!$event) {
            return ['success' => false, 'error' => 'Event not found.'];
        }

        if ($action !== 'approve' && $action !== 'reject') {
            return ['success' => false, 'error' => 'Invalid action.'];
        }

        $status = $action === 'approve' ? 'APPROVED' : 'REJECTED';

        if ($event['APPROVAL_STATUS'] === $status) {
            return ['success' => false, 'error' => 'Event is already ' . strtolower($status) . '.'];
        }

        $this->eventModel->updateApprovalStatus($eventId, $status, trim($remarks));
        return ['success' => true, 'error' => null];
    }

    // Loads data for the Add/Edit Event form
    public function getEventFormData($eventId = null) {
        $organizers = $this->eventModel->getOrganizers();

        $blank = [
            'mode' => 'create',
            'eventId' => null,
            'title' => '',
            'category' => 'ACADEMIC',
            'description' => '',
            'location' => '',
            'venue' => '',
            'date' => '',
            'startTime' => '',
            'endTime' => '',
            'approvalStatus' => 'PENDING',
            'remarks' => '',
            'registrationStatus' => 1,
            'userId' => null,
            'bannerImages' => [],
            'organizers' => $organizers,
        ];

        if ($eventId === null) {
            return $blank;
        }

        $event = $this->eventModel->getEventById($eventId);

        if (!$event) {
            return $blank;
        }

        $bannerImages = [];
        if (!empty($event['BANNER_IMAGE'])) {
            foreach (explode(',', $event['BANNER_IMAGE']) as $img) {
                $img = trim($img);
                if ($img !== '') {
                    $bannerImages[] = $img;
                }
            }
        }

        return [
            'mode' => 'edit',
            'eventId' => (int) $event['EVENT_ID'],
            'title' => $event['TITLE'],
            'category' => $event['CATEGORY'],
            'description' => $event['DESCRIPTION'],
            'location' => $event['LOCATION'],
            'venue' => $event['VENUE'],
            'date' => $event['DATE'],
            'startTime' => $event['START_TIME'],
            'endTime' => $event['END_TIME'],
            'approvalStatus' => $event['APPROVAL_STATUS'],
            'remarks' => $event['REMARKS'],
            'registrationStatus' => (int) $event['REGISTRATION_STATUS'],
            'userId' => (int) $event['USER_ID'],
            'bannerImages' => $bannerImages,
            'organizers' => $organizers,
        ];
    }

    // Creates or updates an event
    public function saveEvent($eventId, $fields) {
        $title = trim($fields['title'] ?? '');
        $category = $fields['category'] ?? '';
        $description = trim($fields['description'] ?? '');
        $location = trim($fields['location'] ?? '');
        $venue = trim($fields['venue'] ?? '');
        $date = $fields['date'] ?? '';
        $startTime = $fields['startTime'] ?? '';
        $endTime = $fields['endTime'] ?? '';
        $approvalStatus = $fields['approvalStatus'] ?? 'PENDING';
        $remarks = trim($fields['remarks'] ?? '');
        $registrationStatus = !empty($fields['registrationStatus']) ? 1 : 0;
        $userId = (int) ($fields['userId'] ?? 0);

        if ($title === '' || $description === '' || $location === '' || $venue === '' ||
            $date === '' || $startTime === '' || $endTime === '' || $userId <= 0) {
            return ['success' => false, 'error' => 'Please fill in all required fields, including the organizer.'];
        }

        $validCategories = ['ACADEMIC', 'NON-ACADEMIC', 'CAREER'];
        if (!in_array($category, $validCategories, true)) {
            return ['success' => false, 'error' => 'Invalid category.'];
        }

        $validStatuses = ['PENDING', 'APPROVED', 'REJECTED'];
        if (!in_array($approvalStatus, $validStatuses, true)) {
            return ['success' => false, 'error' => 'Invalid approval status.'];
        }

        if (strtotime($endTime) !== false && strtotime($startTime) !== false && strtotime($endTime) <= strtotime($startTime)) {
            return ['success' => false, 'error' => 'End time must be after the start time.'];
        }

        $bannerImage = $fields['bannerImage'] ?? '';

        $data = [
            'userId' => $userId,
            'category' => $category,
            'title' => $title,
            'description' => $description,
            'location' => $location,
            'venue' => $venue,
            'date' => $date,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'approvalStatus' => $approvalStatus,
            'remarks' => $remarks,
            'registrationStatus' => $registrationStatus,
            'bannerImage' => $bannerImage,
        ];

        $isEdit = $eventId !== null && $eventId !== '';

        if ($isEdit) {
            $this->eventModel->updateEvent((int) $eventId, $data);
        } else {
            $this->eventModel->createEvent($data);
        }

        return ['success' => true, 'error' => null];
    }

    // Deletes an event
    public function deleteEvent($eventId) {
        return $this->eventModel->deleteEvent((int) $eventId);
    }
}
