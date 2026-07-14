<?php
require_once __DIR__ . '/../models/EventModel.php';
require_once __DIR__ . '/../models/OrganizationModel.php';

/**
 * EventController
 *
 * Coordinates EventModel (+ OrganizationModel for the "Registered Orgs"
 * stat) for the admin "Manage Events" dashboard and the single-event
 * review screen.
 */
class EventController {
    private $eventModel;
    private $orgModel;

    public function __construct() {
        $this->eventModel = new EventModel();
        $this->orgModel = new OrganizationModel();
    }

    /**
     * @param array $filters keys: search, date, category, status
     * @return array{events:array, counts:array, orgsCount:int}
     */
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

    /**
     * @param string $action 'approve' | 'reject'
     * @return array{success:bool, error:?string}
     */
    public function reviewEvent($eventId, $action, $remarks) {
        $event = $this->eventModel->getEventById($eventId);

        if (!$event) {
            return ['success' => false, 'error' => 'Event not found.'];
        }

        if ($action !== 'approve' && $action !== 'reject') {
            return ['success' => false, 'error' => 'Invalid action.'];
        }

        $status = $action === 'approve' ? 'APPROVED' : 'REJECTED';
        $this->eventModel->updateApprovalStatus($eventId, $status, trim($remarks));

        return ['success' => true, 'error' => null];
    }
}
