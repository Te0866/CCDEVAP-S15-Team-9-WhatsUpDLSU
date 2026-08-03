<?php
require_once __DIR__ . '/../models/CommentModel.php';

class CommentController {
    private $commentModel;

    public function __construct() {
        $this->commentModel = new CommentModel();
    }

    // Builds data for the Comments Management table
    public function listComments($search = null, $eventId = null) {
        $comments = $this->commentModel->getAllComments($search, $eventId);
        $rows = [];

        foreach ($comments as $comment) {
            $rows[] = [
                'comment_id' => (int) $comment['COMMENT_ID'],
                'username' => $comment['USERNAME'],
                'text' => $comment['TEXT'],
                'is_anonymous' => (bool) $comment['IS_ANONYMOUS'],
                'event_id' => (int) $comment['EVENT_ID'],
                'event_title' => $comment['EVENT_TITLE'] ?? 'Unknown Event',
            ];
        }

        return $rows;
    }

    public function getEventOptions() {
        return $this->commentModel->getEventOptions();
    }

    // Loads data for the Add/Edit Comment form
    public function getCommentFormData($commentId = null) {
        $events = $this->commentModel->getEventOptions();

        $blank = [
            'mode' => 'create',
            'commentId' => null,
            'eventId' => null,
            'username' => '',
            'text' => '',
            'isAnonymous' => false,
            'events' => $events,
        ];

        if ($commentId === null) {
            return $blank;
        }

        $comment = $this->commentModel->getCommentById($commentId);

        if (!$comment) {
            return $blank;
        }

        return [
            'mode' => 'edit',
            'commentId' => (int) $comment['COMMENT_ID'],
            'eventId' => (int) $comment['EVENT_ID'],
            'username' => $comment['USERNAME'],
            'text' => $comment['TEXT'],
            'isAnonymous' => (bool) $comment['IS_ANONYMOUS'],
            'events' => $events,
        ];
    }

    // Creates or updates a comment
    public function saveComment($commentId, $eventId, $username, $text, $isAnonymous) {
        $username = trim($username);
        $text = trim($text);
        $eventId = (int) $eventId;
        $isAnonymous = $isAnonymous ? 1 : 0;

        if ($username === '' || $text === '' || $eventId <= 0) {
            return ['success' => false, 'error' => 'Event, username, and comment text are required.'];
        }

        if (strlen($text) > 200) {
            return ['success' => false, 'error' => 'Comment text must be 200 characters or fewer.'];
        }

        $isEdit = $commentId !== null && $commentId !== '';

        if ($isEdit) {
            $this->commentModel->updateComment((int) $commentId, $eventId, $username, $text, $isAnonymous);
        } else {
            $this->commentModel->createComment($eventId, $username, $text, $isAnonymous);
        }

        return ['success' => true, 'error' => null];
    }

    // Deletes a comment
    public function deleteComment($commentId) {
        return $this->commentModel->deleteComment((int) $commentId);
    }
}
