<?php
/**
 * LeadDesk Mini - Lead Submission Endpoint
 * Handles the public enquiry form (AJAX POST -> JSON response)
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'message' => 'Invalid request method.'], 405);
}

// ---- CSRF check ----
if (!csrf_verify($_POST['csrf_token'] ?? null)) {
    json_response(['success' => false, 'message' => 'Your session has expired. Please refresh the page and try again.'], 419);
}

// ---- Honeypot spam trap (hidden field that bots tend to fill) ----
if (!empty($_POST['website'])) {
    // Silently pretend success so bots don't learn the trap failed.
    json_response(['success' => true, 'message' => 'Thank you! Your enquiry has been received.']);
}

// ---- Collect + sanitize input ----
$name    = clean_input($_POST['name'] ?? '');
$email   = clean_input($_POST['email'] ?? '');
$budget  = clean_input($_POST['budget'] ?? '');
$message = clean_input($_POST['message'] ?? '');

// ---- Server-side validation ----
$errors = [];

if ($name === '' || mb_strlen($name) < 2) {
    $errors['name'] = 'Please enter your full name.';
}
if ($email === '' || !is_valid_email($email)) {
    $errors['email'] = 'Please enter a valid email address.';
}
if ($budget === '') {
    $errors['budget'] = 'Please select a budget range.';
}
if ($message === '' || mb_strlen($message) < 10) {
    $errors['message'] = 'Please tell us a little more about your project (min. 10 characters).';
}

// Prevent completely empty / junk submissions
if ($name === '' && $email === '' && $message === '') {
    json_response(['success' => false, 'message' => 'Please fill out the form before submitting.'], 422);
}

if (!empty($errors)) {
    json_response(['success' => false, 'message' => 'Please correct the highlighted fields.', 'errors' => $errors], 422);
}

try {
    $pdo = getDbConnection();

    // ---- Prevent duplicate submissions (same email + message within 2 minutes) ----
    $dupCheck = $pdo->prepare(
        'SELECT id FROM leads WHERE email = :email AND message = :message AND created_at >= (NOW() - INTERVAL 2 MINUTE) LIMIT 1'
    );
    $dupCheck->execute(['email' => $email, 'message' => $message]);

    if ($dupCheck->fetch()) {
        json_response(['success' => false, 'message' => 'It looks like you already submitted this enquiry. We will be in touch shortly!'], 409);
    }

    // ---- Insert lead using a prepared statement ----
    $stmt = $pdo->prepare(
        'INSERT INTO leads (name, email, budget, message, status, ip_address, created_at)
         VALUES (:name, :email, :budget, :message, "New", :ip, NOW())'
    );
    $stmt->execute([
        'name'    => $name,
        'email'   => $email,
        'budget'  => $budget,
        'message' => $message,
        'ip'      => $_SERVER['REMOTE_ADDR'] ?? null,
    ]);

    json_response(['success' => true, 'message' => 'Thank you! Your enquiry has been received. Our team will contact you shortly.']);
} catch (PDOException $e) {
    error_log('Lead submission error: ' . $e->getMessage());
    json_response(['success' => false, 'message' => 'Something went wrong. Please try again in a moment.'], 500);
}
