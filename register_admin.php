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
// require_once BASE_PATH . 'functions.php'; // Optional, if needed for helpers

// Load database configuration
$config = require(BASE_PATH . 'config.php');

// --- Database Connection ---
try {
    $db = new Database($config['database']);
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage() . "\n");
}

echo "--- Admin User Registration ---\n";

// --- Seed Default Roles (if they don't exist) ---
echo "\nChecking and seeding default roles...\n";
$defaultRoles = [
    ['name' => 'Administrator',     'description' => 'Full system access.'],
    ['name' => 'Admission Officer', 'description' => 'Manages student data and applications.'],
    ['name' => 'Question Manager',  'description' => 'Manages test subjects and questions.'],
    // Add more essential default roles here if needed
];

try {
    $rolesSeeded = 0;
    foreach ($defaultRoles as $role) {
        // Check if role exists by name (using unique constraint)
        $stmtCheck = $db->query("SELECT role_id FROM roles WHERE role_name = :role_name", [
            'role_name' => $role['name']
        ]);

        if (!$stmtCheck->fetch()) {
            // Role does not exist, insert it
            echo "  -> Seeding role: '{$role['name']}'...\n";
            $db->query(
                "INSERT INTO roles (role_name, description) VALUES (:role_name, :description)",
                [
                    'role_name'   => $role['name'],
                    'description' => $role['description'] ?? null // Use null if description isn't set
                ]
            );
            $rolesSeeded++;
        }
    }
    if ($rolesSeeded > 0) {
        echo "  {$rolesSeeded} role(s) seeded.\n";
    } else {
        echo "  Default roles already exist.\n";
    }
    echo "Role seeding check complete.\n";

} catch (PDOException $e) {
    // If seeding fails, we probably can't continue reliably
    die("Database Error during role seeding: " . $e->getMessage() . "\n");
}
// --- End Role Seeding ---


// --- Fetch Available Roles (Now guaranteed to have defaults) ---
try {
    // Fetch roles again AFTER potentially seeding them
    $stmtRoles = $db->query("SELECT role_id, role_name FROM roles ORDER BY role_name");
    $availableRoles = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

    // This check should technically not be strictly needed anymore if seeding works,
    // but it's good defense in case seeding failed silently (though we added a die above).
    if (empty($availableRoles)) {
        die("Critical Error: No roles found even after seeding attempt. Check database connection and permissions.\n");
    }

} catch (PDOException $e) {
    die("Database Error fetching roles: " . $e->getMessage() . "\n");
}


// --- Get User Input ---
$username = trim(readline("Enter username: "));
$password = trim(readline("Enter password: "));
$confirmPassword = trim(readline("Confirm password: "));
$fullName = trim(readline("Enter full name: "));
$email = trim(readline("Enter email: "));

// --- Display Available Roles and Get Selection ---
echo "\nAvailable Roles:\n";
$validRoleIds = []; // Keep track of valid IDs for validation
foreach ($availableRoles as $role) {
    echo "  [{$role['role_id']}] {$role['role_name']}\n";
    $validRoleIds[] = $role['role_id']; // Store the valid ID
}

$selectedRoleId = null;
while ($selectedRoleId === null) {
    $inputRoleId = trim(readline("Enter the ID of the role for this user: "));

    // Basic validation: is it a number and is it in our list?
    if (!ctype_digit($inputRoleId) || !in_array((int)$inputRoleId, $validRoleIds)) {
        echo "Invalid Role ID. Please choose a valid ID from the list above.\n";
    } else {
        $selectedRoleId = (int)$inputRoleId; // Cast to integer
    }
}

// --- Basic Input Validation (Other fields) ---
if (empty($username) || empty($password) || empty($confirmPassword) || empty($fullName) || empty($email)) {
    die("Error: Username, password, full name, and email fields are required.\n");
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
            'role_id' => $selectedRoleId // Use the validated selected role ID
        ]
    );

    echo "\nAdmin user '{$username}' created successfully!\n";

} catch (PDOException $e) {
    die("\nDatabase Error: Failed to create admin user. " . $e->getMessage() . "\n");
}

?>