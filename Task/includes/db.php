<?php
/**
 * LeadDesk Mini - Database Connection
 * Uses PDO with prepared statements for all queries.
 */

// ---- Database configuration ----
// Update these values to match your hosting environment.
define('DB_HOST', 'sql209.infinityfree.com');
define('DB_NAME', 'if0_40226469_leaddesk');
define('DB_USER', 'if0_40226469');
define('DB_PASS', 'Abhilash509');   // Use your actual MySQL password
define('DB_CHARSET', 'utf8mb4');

function getDbConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // real prepared statements
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Never leak connection details to the client.
            error_log('DB Connection Error: ' . $e->getMessage());
       die($e->getMessage());
        }
    }

    return $pdo;
}
