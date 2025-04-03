<?php
// Student exam results page (student/results.php)
require_once __DIR__ . '/../bootstrap.php';

// Require login
requireStudentLogin();

// Get student information
$studentId = $_SESSION['student_id'];

// Get the latest completed test attempt
$attemptStatement = $db->query(
    "SELECT * FROM test_attempts 
     WHERE student_id = ? 
     AND (status = 'Completed' OR status = 'Expired') 
     ORDER BY created_at DESC LIMIT 1",
    [$studentId]
);
$attempt = $attemptStatement->fetch();

// If no completed attempt is found, redirect to dashboard
if (!$attempt) {
    redirect('/student/dashboard.php', 'No completed exam found.', 'error');
}

// Calculate test duration (just for display)
$startTime = strtotime($attempt['start_time']);
$endTime = strtotime($attempt['end_time']);
$duration = $endTime - $startTime;
$hours = floor($duration / 3600);
$minutes = floor(($duration % 3600) / 60);
$seconds = $duration % 60;

$title = "Exam Completed - ECAT System";
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
            max-width: 800px;
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
            border-radius: 8px;
        }
        
        .header-title {
            margin: 0;
            font-size: 24px;
        }
        
        .results-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 40px 30px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .congrats-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .congrats-title {
            color: #4CAF50;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 36px;
        }
        
        .congrats-message {
            color: #555;
            font-size: 18px;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .completion-details {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            display: inline-block;
        }
        
        .completion-detail {
            margin: 8px 0;
            color: #666;
        }
        
        .button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        
        .button:hover {
            background-color: #45a049;
        }
        
        .button-large {
            font-size: 18px;
            padding: 15px 30px;
        }
        
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            animation: fall linear forwards;
        }
        
        @keyframes fall {
            to {
                transform: translateY(100vh);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1 class="header-title">Exam Completed</h1>
        </header>
        
        <?php flashMessage(); ?>
        
        <div class="results-card">
            <div class="congrats-icon">🎉</div>
            <h1 class="congrats-title">Congratulations!</h1>
            <p class="congrats-message">
                You have successfully completed the ECAT examination.<br>
                Thank you for your participation.
            </p>
            
            <div class="completion-details">
                <div class="completion-detail">
                    <strong>Completed on:</strong> <?= date('F d, Y h:i A', strtotime($attempt['end_time'])) ?>
                </div>
                <div class="completion-detail">
                    <strong>Duration:</strong> 
                    <?= $hours > 0 ? $hours . ' hour' . ($hours > 1 ? 's' : '') . ', ' : '' ?>
                    <?= $minutes ?> minute<?= $minutes != 1 ? 's' : '' ?>, 
                    <?= $seconds ?> second<?= $seconds != 1 ? 's' : '' ?>
                </div>
                <div class="completion-detail">
                    <strong>Status:</strong> <?= $attempt['status'] ?>
                </div>
            </div>
            
            <a href="/student/dashboard.php" class="button button-large">Back to Dashboard</a>
        </div>
    </div>
    
    <script>
        // Create confetti animation
        function createConfetti() {
            const colors = ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4CAF50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107', '#ff9800', '#ff5722'];
            
            for (let i = 0; i < 100; i++) {
                const confetti = document.createElement('div');
                confetti.classList.add('confetti');
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
                confetti.style.animationDelay = Math.random() * 5 + 's';
                document.body.appendChild(confetti);
            }
        }
        
        // Run on page load
        document.addEventListener('DOMContentLoaded', createConfetti);
    </script>
</body>
</html>