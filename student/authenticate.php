<?php
// Student authentication script (student/authenticate.php)
require_once __DIR__ . '/../bootstrap.php';

// Redirect if already logged in
if (isStudentLoggedIn()) {
    redirect('/student/dashboard.php');
}

// Process the login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (!isset($_POST['passcode']) || empty($_POST['passcode'])) {
        redirect('/student/login.php', 'Passcode is required', 'error');
    }
    
    $passcode = trim($_POST['passcode']);
    
    // Check passcode in database
    $statement = $db->query(
        "SELECT student_id, passcode, first_name, last_name FROM students WHERE passcode = ?",
        [$passcode]
    );
    
    $student = $statement->fetch();
    
    if ($student) {
        // Login successful - store user data in session
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
        
        // Redirect to dashboard
        redirect('/student/dashboard.php', 'Login successful', 'success');
    } else {
        // Login failed
        redirect('/student/login.php', 'Invalid passcode. Please try again.', 'error');
    }
} else {
    // Not a POST request, redirect to login page
    redirect('/student/login.php');
}