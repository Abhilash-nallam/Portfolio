<?php
/**
 * LeadDesk Mini - Shared Helper Functions
 */

/**
 * Sanitize a plain-text input string.
 */
function clean_input(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate an email address.
 */
function is_valid_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Escape output for safe HTML rendering.
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Return a small badge class for a given lead status.
 */
function status_badge_class(string $status): string
{
    switch ($status) {
        case 'New':
            return 'badge-status-new';
        case 'Contacted':
            return 'badge-status-contacted';
        case 'Closed':
            return 'badge-status-closed';
        default:
            return 'badge-status-new';
    }
}

/**
 * Format a datetime string into a friendly display format.
 */
function format_date(string $datetime): string
{
    $ts = strtotime($datetime);
    return $ts ? date('d M Y, h:i A', $ts) : $datetime;
}

/**
 * Send a JSON response and terminate execution.
 */
function json_response(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}
