<?php
// Admin authentication script (admin/authenticate.php)
require_once __DIR__ . '/../bootstrap.php';

// Redirect if already logged in
if (isAdminLoggedIn()) {
    redirect('/admin/dashboard.php');
}

// Process the login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (!isset($_POST['username']) || empty($_POST['username']) || 
        !isset($_POST['password']) || empty($_POST['password'])) {
        redirect('/admin/login.php', 'Username and password are required', 'error');
    }
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Get user from database
    $statement = $db->query(
        "SELECT user_id, username, password_hash, full_name, role_id, is_active FROM admin_users WHERE username = ?",
        [$username]
    );
    
    $admin = $statement->fetch();
    
    // Check if user exists and is active
    if (!$admin) {
        redirect('/admin/login.php', 'Invalid username or password', 'error');
    }
    
    if (!$admin['is_active']) {
        redirect('/admin/login.php', 'Your account has been deactivated. Please contact the system administrator.', 'error');
    }
    
    // Verify password
    if (password_verify($password, $admin['password_hash'])) {
        // Login successful
        
        // Update last login time
        $db->query(
            "UPDATE admin_users SET last_login = NOW() WHERE user_id = ?",
            [$admin['user_id']]
        );
        
        // Get role information
        $roleStatement = $db->query(
            "SELECT role_name FROM roles WHERE role_id = ?",
            [$admin['role_id']]
        );
        
        $role = $roleStatement->fetch();
        
        // Store user data in session
        $_SESSION['admin_id'] = $admin['user_id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        $_SESSION['admin_role'] = $role['role_name'] ?? 'Unknown Role';
        $_SESSION['admin_role_id'] = $admin['role_id'];
        
        // Redirect to dashboard
        redirect('/admin/dashboard.php', 'Login successful', 'success');
    } else {
        // Password incorrect
        redirect('/admin/login.php', 'Invalid username or password', 'error');
    }
} else {
    // Not a POST request, redirect to login page
    redirect('/admin/login.php');
}