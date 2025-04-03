<?php
/**
 * Admin User Creation Script
 * 
 * This script creates an administrator user in the admin_users table.
 * It ensures the necessary role exists before creating the user.
 */

// Include bootstrap for database connection
require_once __DIR__ . '/bootstrap.php';

// Admin user details - CHANGE THESE VALUES
$username = 'admin';
$password = 'admin'; // This will be hashed before storing
$fullName = 'System Administrator';
$email = 'admin@example.com';
$roleName = 'Administrator'; // This should match a role in the roles table or will be created

// Check if the role exists, if not create it
$roleStatement = $db->query(
    "SELECT role_id FROM roles WHERE role_name = ?",
    [$roleName]
);
$role = $roleStatement->fetch();

if (!$role) {
    // Create the role if it doesn't exist
    $db->query(
        "INSERT INTO roles (role_name, description) VALUES (?, ?)",
        [$roleName, 'Full system access with all permissions']
    );
    
    // Get the newly created role ID
    $roleStatement = $db->query(
        "SELECT role_id FROM roles WHERE role_name = ?",
        [$roleName]
    );
    $role = $roleStatement->fetch();
    
    echo "Created new role: {$roleName} with ID: {$role['role_id']}\n";
}

// Check if username already exists
$userStatement = $db->query(
    "SELECT user_id FROM admin_users WHERE username = ?",
    [$username]
);
$existingUser = $userStatement->fetch();

if ($existingUser) {
    echo "Error: Username '{$username}' already exists.\n";
    exit;
}

// Check if email already exists
$emailStatement = $db->query(
    "SELECT user_id FROM admin_users WHERE email = ?",
    [$email]
);
$existingEmail = $emailStatement->fetch();

if ($existingEmail) {
    echo "Error: Email '{$email}' already exists.\n";
    exit;
}

// Hash the password for secure storage
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Create the admin user
try {
    $db->query(
        "INSERT INTO admin_users (username, password_hash, full_name, email, role_id, is_active) 
         VALUES (?, ?, ?, ?, ?, ?)",
        [$username, $passwordHash, $fullName, $email, $role['role_id'], 1]
    );
    
    echo "Success! Admin user '{$username}' created successfully.\n";
    echo "You can now login at: /admin/login.php\n";
    echo "Username: {$username}\n";
    echo "Password: {$password}\n";
    echo "Note: Please change the default password after your first login for security reasons.\n";
} catch (PDOException $e) {
    echo "Error creating admin user: " . $e->getMessage() . "\n";
}