<?php
// Admin logout script (admin/logout.php)
require_once __DIR__ . '/../bootstrap.php';

// Clear all session data
session_unset();
session_destroy();

// Redirect to login page
redirect('/login.php', 'You have been logged out successfully', 'success');