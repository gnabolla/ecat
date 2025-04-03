<?php
/**
 * Bootstrap file to handle common initialization and database connection
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load functions
require_once __DIR__ . '/functions.php';

// Load Database class
require_once __DIR__ . '/Database.php';

// Load configuration
$config = require __DIR__ . '/config.php';

// Initialize database connection
$db = new Database($config['database'], 'root', '');

/**
 * Redirect to a URL with an optional flash message
 *
 * @param string $url URL to redirect to
 * @param string|null $message Optional message to display
 * @param string $type Message type (success, error, warning, info)
 * @return void
 */
function redirect(string $url, ?string $message = null, string $type = 'info'): void
{
    if ($message) {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type
        ];
    }
    
    header("Location: $url");
    exit;
}

/**
 * Display flash message if exists and clear it
 *
 * @return void
 */
function flashMessage(): void
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        $type = $flash['type'];
        $message = $flash['message'];
        
        echo "<div class='flash-message $type'>$message</div>";
        
        // Clear the flash message
        unset($_SESSION['flash']);
    }
}

/**
 * Check if user is logged in as admin
 *
 * @return bool
 */
function isAdminLoggedIn(): bool
{
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Check if user is logged in as student
 *
 * @return bool
 */
function isStudentLoggedIn(): bool
{
    return isset($_SESSION['student_id']) && !empty($_SESSION['student_id']);
}

/**
 * Require admin login or redirect
 *
 * @return void
 */
function requireAdminLogin(): void
{
    if (!isAdminLoggedIn()) {
        redirect('/admin/login.php', 'Please log in to access this page', 'error');
    }
}

/**
 * Require student login or redirect
 *
 * @return void
 */
function requireStudentLogin(): void
{
    if (!isStudentLoggedIn()) {
        redirect('/student/login.php', 'Please log in to access this page', 'error');
    }
}