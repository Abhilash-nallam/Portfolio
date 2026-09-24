<?php
/**
 * LeadDesk Mini - Authentication Helper
 * Handles secure session start, login checks, and session timeout.
 */

// Session lifetime in seconds (30 minutes of inactivity)
define('SESSION_TIMEOUT', 1800);

if (session_status() === PHP_SESSION_NONE) {
    // Harden session cookie settings before starting the session.
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        // 'secure' => true, // enable this once served over HTTPS
    ]);
    session_start();
}

/**
 * Returns true if an admin is currently logged in (and session hasn't timed out).
 */
function is_logged_in(): bool
{
    if (empty($_SESSION['admin_id'])) {
        return false;
    }

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        return false;
    }

    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * Redirects to the login page if the admin is not authenticated.
 * Call this at the top of every protected admin page.
 */
function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php?expired=1');
        exit;
    }
}

/**
 * Logs an admin in by populating the session.
 */
function login_admin(array $admin): void
{
    session_regenerate_id(true); // prevent session fixation
    $_SESSION['admin_id']       = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['admin_name']     = $admin['full_name'] ?? $admin['username'];
    $_SESSION['last_activity']  = time();
}

/**
 * Logs the current admin out.
 */
function logout_admin(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
