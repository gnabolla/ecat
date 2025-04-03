<?php
// Student dashboard page (student/dashboard.php)
require_once __DIR__ . '/../bootstrap.php';

// Require login
requireStudentLogin();

// Get student information
$studentId = $_SESSION['student_id'];
$statement = $db->query(
    "SELECT * FROM students WHERE student_id = ?",
    [$studentId]
);
$student = $statement->fetch();

// Get student's latest test attempt if any
$attemptStatement = $db->query(
    "SELECT * FROM test_attempts WHERE student_id = ? ORDER BY created_at DESC LIMIT 1",
    [$studentId]
);
$latestAttempt = $attemptStatement->fetch();

$title = "Student Dashboard - ECAT System";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .welcome {
            margin: 0;
        }
        
        .logout a {
            color: white;
            text-decoration: none;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 8px 15px;
            border-radius: 4px;
        }
        
        .logout a:hover {
            background-color: rgba(0, 0, 0, 0.3);
        }
        
        .dashboard-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .dashboard-card h2 {
            color: #333;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .user-profile {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .user-profile p {
            margin: 10px 0;
        }
        
        .user-profile .label {
            font-weight: bold;
            color: #666;
        }
        
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
            text-decoration: none;
        }
        
        .button:hover {
            background-color: #45a049;
        }
        
        .flash-message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .flash-message.error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        
        .flash-message.success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
    </style>
</head>
<body>
    <header>
        <h1 class="welcome">Welcome, <?= htmlspecialchars($_SESSION['student_name']) ?></h1>
        <div class="logout">
            <a href="/student/logout.php">Logout</a>
        </div>
    </header>
    
    <div class="container">
        <?php flashMessage(); ?>
        
        <div class="dashboard-card">
            <h2>Your Profile</h2>
            <div class="user-profile">
                <div>
                    <p><span class="label">Name:</span> <?= htmlspecialchars($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']) ?></p>
                    <p><span class="label">Passcode:</span> <?= htmlspecialchars($student['passcode']) ?></p>
                    <p><span class="label">LRN:</span> <?= htmlspecialchars($student['lrn'] ?? 'Not set') ?></p>
                    <p><span class="label">GWA:</span> <?= htmlspecialchars($student['gwa'] ?? 'Not set') ?></p>
                </div>
                <div>
                    <p><span class="label">Email:</span> <?= htmlspecialchars($student['email'] ?? 'Not set') ?></p>
                    <p><span class="label">Contact Number:</span> <?= htmlspecialchars($student['contact_number'] ?? 'Not set') ?></p>
                    <p><span class="label">Sex:</span> <?= htmlspecialchars($student['sex'] ?? 'Not set') ?></p>
                    <p><span class="label">Birthday:</span> <?= htmlspecialchars($student['birthday'] ?? 'Not set') ?></p>
                </div>
            </div>
        </div>
        
        <div class="dashboard-card">
            <h2>ECAT Status</h2>
            <?php if ($latestAttempt): ?>
                <p><span class="label">Latest Attempt:</span> <?= htmlspecialchars($latestAttempt['created_at']) ?></p>
                <p><span class="label">Status:</span> <?= htmlspecialchars($latestAttempt['status']) ?></p>
                <?php if ($latestAttempt['status'] === 'Completed'): ?>
                    <p><span class="label">Score:</span> <?= htmlspecialchars($latestAttempt['total_score'] ?? 'Pending') ?></p>
                    <a href="/student/results.php" class="button">View Detailed Results</a>
                <?php elseif ($latestAttempt['status'] === 'In Progress'): ?>
                    <a href="/student/test.php" class="button">Continue Test</a>
                <?php else: ?>
                    <p>Your test has not been started yet.</p>
                    <a href="/student/test.php" class="button">Start Test</a>
                <?php endif; ?>
            <?php else: ?>
                <p>You have not taken any ECAT tests yet.</p>
                <a href="/student/test.php" class="button">Take Test</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>