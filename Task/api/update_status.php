<?php
/**
 * LeadDesk Mini - Update Lead Status (Admin only, AJAX)
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

if (!is_logged_in()) {
    json_response(['success' => false, 'message' => 'Unauthorized. Please log in again.'], 401);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method.'], 405);
}

if (!csrf_verify($_POST['csrf_token'] ?? null)) {
    json_response(['success' => false, 'message' => 'Invalid or expired session token.'], 419);
}

$leadId    = filter_var($_POST['lead_id'] ?? null, FILTER_VALIDATE_INT);
$newStatus = $_POST['status'] ?? '';

$allowedStatuses = ['New', 'Contacted', 'Closed'];

if (!$leadId || !in_array($newStatus, $allowedStatuses, true)) {
    json_response(['success' => false, 'message' => 'Invalid lead or status value.'], 422);
}

try {
    $pdo  = getDbConnection();
    $stmt = $pdo->prepare('UPDATE leads SET status = :status WHERE id = :id');
    $stmt->execute(['status' => $newStatus, 'id' => $leadId]);

    if ($stmt->rowCount() === 0) {
        json_response(['success' => false, 'message' => 'Lead not found.'], 404);
    }

    json_response(['success' => true, 'message' => 'Lead status updated to "' . $newStatus . '".']);
} catch (PDOException $e) {
    error_log('Status update error: ' . $e->getMessage());
    json_response(['success' => false, 'message' => 'Could not update the lead. Please try again.'], 500);
}
