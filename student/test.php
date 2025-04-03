<?php
// Student test start/continuation page (student/test.php)
require_once __DIR__ . '/../bootstrap.php';

// Require login
requireStudentLogin();

// Get student information
$studentId = $_SESSION['student_id'];

// Check if profile is complete
$studentStatement = $db->query(
    "SELECT * FROM students WHERE student_id = ?",
    [$studentId]
);
$student = $studentStatement->fetch();

// Redirect if profile is incomplete
if (!isProfileComplete($student)) {
    redirect('/student/complete_profile.php', 'You must complete your profile before taking the exam', 'error');
}

// Get the latest test attempt or create a new one
$attemptStatement = $db->query(
    "SELECT * FROM test_attempts WHERE student_id = ? ORDER BY created_at DESC LIMIT 1",
    [$studentId]
);
$attempt = $attemptStatement->fetch();

// If no attempt exists or the last attempt is completed/expired, create a new one
if (!$attempt || in_array($attempt['status'], ['Completed', 'Expired', 'Aborted'])) {
    // Create a new attempt
    $db->query(
        "INSERT INTO test_attempts (student_id, status, created_at) VALUES (?, 'Not Started', NOW())",
        [$studentId]
    );
    
    // Get the newly created attempt
    $attemptStatement = $db->query(
        "SELECT * FROM test_attempts WHERE student_id = ? ORDER BY created_at DESC LIMIT 1",
        [$studentId]
    );
    $attempt = $attemptStatement->fetch();
}

// If the exam is already in progress but time has expired, update status
if ($attempt['status'] === 'In Progress' && $attempt['start_time'] && $attempt['start_time'] !== null) {
    // Calculate exam duration (e.g., 2 hours = 7200 seconds)
    $examDuration = 7200; // 2 hours in seconds
    $startTime = strtotime($attempt['start_time']);
    $currentTime = time();
    
    if (($currentTime - $startTime) > $examDuration) {
        // Exam time has expired, update status
        $db->query(
            "UPDATE test_attempts SET status = 'Expired', end_time = FROM_UNIXTIME(?) WHERE attempt_id = ?",
            [$startTime + $examDuration, $attempt['attempt_id']]
        );
        
        redirect('/student/results.php', 'Your exam time has expired', 'error');
    }
}

// If "Start Exam" button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_exam'])) {
    // Update attempt to mark as started
    $db->query(
        "UPDATE test_attempts SET status = 'In Progress', start_time = NOW() WHERE attempt_id = ?",
        [$attempt['attempt_id']]
    );
    
    // Get all subjects
    $subjectsStatement = $db->query("SELECT id, name FROM subjects ORDER BY name");
    $subjects = $subjectsStatement->fetchAll();
    
    // Randomize subject order
    shuffle($subjects);
    
    // Store randomized subject order in session
    $_SESSION['exam_subjects_order'] = array_column($subjects, 'id');
    
    // For each subject, get and randomize questions
    $_SESSION['exam_questions_order'] = [];
    foreach ($subjects as $subject) {
        $questionsStatement = $db->query(
            "SELECT id FROM questions WHERE subject_id = ? ORDER BY id",
            [$subject['id']]
        );
        $questions = array_column($questionsStatement->fetchAll(), 'id');
        
        // Special handling for passage-based questions (ID 13, 14, 15)
        // Keep them together and in order regardless of randomization
        $passageBasedQuestionIds = [13, 14, 15];
        
        // Check if any of the passage-based questions are in this subject
        $passageBasedQuestions = array_intersect($questions, $passageBasedQuestionIds);
        
        if (!empty($passageBasedQuestions)) {
            // Remove the passage-based questions from the array
            $questions = array_diff($questions, $passageBasedQuestionIds);
            
            // Randomize the remaining questions
            shuffle($questions);
            
            // Get only the passage-based questions that are in this subject and sort them
            $passageQuestionsInOrder = array_intersect($passageBasedQuestionIds, $passageBasedQuestions);
            sort($passageQuestionsInOrder);
            
            // Add the passage-based questions to the beginning of the array to keep them together
            $questions = array_merge($passageQuestionsInOrder, $questions);
        } else {
            // No passage-based questions in this subject, just randomize all questions
            shuffle($questions);
        }
        
        // Store randomized question order
        $_SESSION['exam_questions_order'][$subject['id']] = $questions;
    }
    
    // Redirect to exam page
    redirect('/student/exam.php');
}

$title = "ECAT Examination - ECAT System";
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
        }
        
        .welcome {
            margin: 0;
        }
        
        .back-link a {
            color: white;
            text-decoration: none;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 8px 15px;
            border-radius: 4px;
        }
        
        .back-link a:hover {
            background-color: rgba(0, 0, 0, 0.3);
        }
        
        .exam-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .exam-card h2 {
            color: #333;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .exam-instructions {
            margin-bottom: 20px;
        }
        
        .exam-instructions h3 {
            color: #4CAF50;
        }
        
        .exam-instructions ul {
            padding-left: 20px;
        }
        
        .exam-instructions li {
            margin-bottom: 10px;
        }
        
        .warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
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
        
        .button.large {
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
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
        <h1 class="welcome">ECAT Examination</h1>
        <div class="back-link">
            <a href="/student/dashboard.php">Back to Dashboard</a>
        </div>
    </header>
    
    <div class="container">
        <?php flashMessage(); ?>
        
        <div class="exam-card">
            <h2>Entrance Examination</h2>
            
            <?php if ($attempt['status'] === 'In Progress'): ?>
                <div class="warning">
                    <strong>Attention:</strong> You have an exam in progress. You can continue where you left off.
                </div>
                
                <h3>Time Remaining</h3>
                <?php
                    // Calculate time remaining
                    $examDuration = 7200; // 2 hours in seconds
                    $startTime = strtotime($attempt['start_time']);
                    $currentTime = time();
                    $timeElapsed = $currentTime - $startTime;
                    $timeRemaining = max(0, $examDuration - $timeElapsed);
                    
                    // Format time remaining
                    $hoursRemaining = floor($timeRemaining / 3600);
                    $minutesRemaining = floor(($timeRemaining % 3600) / 60);
                ?>
                <p>Time Remaining: <strong><?= $hoursRemaining ?> hours, <?= $minutesRemaining ?> minutes</strong></p>
                
                <a href="/student/exam.php" class="button large">Continue Exam</a>
                
            <?php else: ?>
                <div class="exam-instructions">
                    <h3>Instructions</h3>
                    <ul>
                        <li>This exam consists of multiple-choice questions from various subjects.</li>
                        <li>The exam duration is <strong>2 hours (120 minutes)</strong>.</li>
                        <li>Once you start the exam, the timer will begin counting down.</li>
                        <li>You can navigate between questions using the provided navigation buttons.</li>
                        <li>Your answers are automatically saved when you move to another question.</li>
                        <li>You can submit the exam when you've answered all questions or when the time expires.</li>
                        <li>After submission, you will be able to see your results.</li>
                        <li><strong>Some questions are based on reading passages. Read all passages carefully before answering the related questions.</strong></li>
                    </ul>
                </div>
                
                <div class="warning">
                    <strong>Important:</strong> Ensure you have a stable internet connection before starting the exam. Do not refresh the page or close the browser during the exam as it may result in loss of answers.
                </div>
                
                <form method="post" action="">
                    <input type="hidden" name="start_exam" value="1">
                    <button type="submit" class="button large">Start Exam Now</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>