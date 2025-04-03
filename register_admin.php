#!/usr/bin/env php
<?php

// Only allow running from the command line
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.\n");
}

// Set base path assuming the script is in the project root
define('BASE_PATH', __DIR__ . '/');

// Include necessary files
require_once BASE_PATH . 'Database.php';
require_once BASE_PATH . 'functions.php'; // Optional, if needed for helpers

// Load database configuration
$config = require(BASE_PATH . 'config.php');

// --- Database Connection ---
try {
    $db = new Database($config['database']);
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage() . "\n");
}

echo "--- Admin User Registration ---\n";

// --- Get User Input ---
$username = trim(readline("Enter username: "));
$password = trim(readline("Enter password: "));
$confirmPassword = trim(readline("Confirm password: "));
$fullName = trim(readline("Enter full name: "));
$email = trim(readline("Enter email: "));
$roleId = 1; // Default admin role ID - adjust if necessary

// --- Basic Input Validation ---
if (empty($username) || empty($password) || empty($confirmPassword) || empty($fullName) || empty($email)) {
    die("Error: All fields are required.\n");
}

if ($password !== $confirmPassword) {
    die("Error: Passwords do not match.\n");
}

if (strlen($password) < 8) { // Basic password strength check
    echo "Warning: Password is less than 8 characters long.\n";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Error: Invalid email format.\n");
}


// --- Check for Existing User/Email ---
try {
    // Check username
    $stmt = $db->query("SELECT user_id FROM admin_users WHERE username = :username", ['username' => $username]);
    if ($stmt->fetch()) {
        die("Error: Username '{$username}' already exists.\n");
    }

    // Check email
    $stmt = $db->query("SELECT user_id FROM admin_users WHERE email = :email", ['email' => $email]);
    if ($stmt->fetch()) {
        die("Error: Email '{$email}' already exists.\n");
    }

} catch (PDOException $e) {
    die("Database Error during validation: " . $e->getMessage() . "\n");
}

// --- Hash Password ---
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

if ($passwordHash === false) {
    die("Error: Failed to hash password. Please check PHP configuration.\n");
}

// --- Insert User into Database ---
try {
    $db->query(
        "INSERT INTO admin_users (username, password_hash, full_name, email, role_id, is_active, created_at, updated_at)
         VALUES (:username, :password_hash, :full_name, :email, :role_id, 1, NOW(), NOW())",
        [
            'username' => $username,
            'password_hash' => $passwordHash,
            'full_name' => $fullName,
            'email' => $email,
            'role_id' => $roleId // Using the default role ID
        ]
    );

    echo "\nAdmin user '{$username}' created successfully!\n";

} catch (PDOException $e) {
    die("\nDatabase Error: Failed to create admin user. " . $e->getMessage() . "\n");
}

?>