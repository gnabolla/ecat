<?php
// Student exam review page (student/exam_review.php)
require_once __DIR__ . '/../bootstrap.php';

// Require login
requireStudentLogin();

// Get student information
$studentId = $_SESSION['student_id'];

// Get the current test attempt
$attemptStatement = $db->query(
    "SELECT * FROM test_attempts 
     WHERE student_id = ? 
     AND status = 'In Progress' 
     ORDER BY created_at DESC LIMIT 1",
    [$studentId]
);
$attempt = $attemptStatement->fetch();

// If no in-progress attempt is found, redirect to test page
if (!$attempt) {
    redirect('/student/test.php', 'No active exam found. Please start a new exam.', 'error');
}

// Check if time has expired
$examDuration = 7200; // 2 hours in seconds
$startTime = strtotime($attempt['start_time']);
$currentTime = time();
$timeElapsed = $currentTime - $startTime;
$timeRemaining = max(0, $examDuration - $timeElapsed);

if ($timeRemaining <= 0) {
    // Update attempt status to expired
    $db->query(
        "UPDATE test_attempts SET status = 'Expired', end_time = FROM_UNIXTIME(?) WHERE attempt_id = ?",
        [$startTime + $examDuration, $attempt['attempt_id']]
    );
    
    redirect('/student/results.php', 'Your exam time has expired', 'error');
}

// Get all subjects
$subjectsStatement = $db->query("SELECT id, name FROM subjects ORDER BY name");
$allSubjects = $subjectsStatement->fetchAll();

// We use alphabetical order here for consistency with other pages
// No need to randomize here as this is just for review purposes

// Submit exam action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_exam'])) {
    // Update answers with correct/incorrect status
    $db->query(
        "UPDATE student_answers sa
         JOIN questions q ON sa.question_id = q.id
         JOIN subjects s ON q.subject_id = s.id
         SET sa.is_correct = CASE
            WHEN s.name LIKE '%abstract reasoning%' THEN 
                CASE WHEN sa.selected_choice = q.correct_answer THEN 1 ELSE 0 END
            ELSE
                CASE
                    WHEN sa.selected_choice = 'choice1' AND q.correct_answer = q.choice1 THEN 1
                    WHEN sa.selected_choice = 'choice2' AND q.correct_answer = q.choice2 THEN 1
                    WHEN sa.selected_choice = 'choice3' AND q.correct_answer = q.choice3 THEN 1
                    WHEN sa.selected_choice = 'choice4' AND q.correct_answer = q.choice4 THEN 1
                    ELSE 0
                END
         END
         WHERE sa.attempt_id = ?",
        [$attempt['attempt_id']]
    );
    
    // Calculate total score
    $scoreStatement = $db->query(
        "SELECT COUNT(*) as total_correct FROM student_answers 
         WHERE attempt_id = ? AND is_correct = 1",
        [$attempt['attempt_id']]
    );
    $totalCorrect = $scoreStatement->fetch()['total_correct'];
    
    // Update attempt status
    $db->query(
        "UPDATE test_attempts 
         SET status = 'Completed', end_time = NOW(), total_score = ? 
         WHERE attempt_id = ?",
        [$totalCorrect, $attempt['attempt_id']]
    );
    
    // Calculate subject scores
    foreach ($allSubjects as $subject) {
        $subjectScoreStatement = $db->query(
            "SELECT 
                COUNT(*) as items_attempted,
                SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as items_correct
             FROM student_answers sa
             JOIN questions q ON sa.question_id = q.id
             WHERE sa.attempt_id = ? AND q.subject_id = ?",
            [$attempt['attempt_id'], $subject['id']]
        );
        $subjectScore = $subjectScoreStatement->fetch();
        
        if ($subjectScore && $subjectScore['items_attempted'] > 0) {
            // Insert or update subject score
            $db->query(
                "INSERT INTO attempt_scores_by_subject 
                 (attempt_id, subject_id, score, items_attempted, items_correct)
                 VALUES (?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE
                 score = VALUES(score),
                 items_attempted = VALUES(items_attempted),
                 items_correct = VALUES(items_correct)",
                [
                    $attempt['attempt_id'], 
                    $subject['id'], 
                    $subjectScore['items_correct'], 
                    $subjectScore['items_attempted'],
                    $subjectScore['items_correct']
                ]
            );
        }
    }
    
    redirect('/student/results.php', 'Your exam has been submitted successfully', 'success');
}

// Get statistics about the exam
$totalQuestionsStatement = $db->query("SELECT COUNT(*) as count FROM questions");
$totalQuestions = $totalQuestionsStatement->fetch()['count'];

$answeredQuestionsStatement = $db->query(
    "SELECT COUNT(*) as count FROM student_answers WHERE attempt_id = ?",
    [$attempt['attempt_id']]
);
$answeredQuestions = $answeredQuestionsStatement->fetch()['count'];

$unansweredQuestions = $totalQuestions - $answeredQuestions;

// Get subject breakdown of answered/unanswered questions
$subjectStats = [];
foreach ($allSubjects as $subject) {
    $subjectQuestionsStatement = $db->query(
        "SELECT COUNT(*) as count FROM questions WHERE subject_id = ?",
        [$subject['id']]
    );
    $totalSubjectQuestions = $subjectQuestionsStatement->fetch()['count'];
    
    $answeredSubjectQuestionsStatement = $db->query(
        "SELECT COUNT(*) as count FROM student_answers sa
         JOIN questions q ON sa.question_id = q.id
         WHERE sa.attempt_id = ? AND q.subject_id = ?",
        [$attempt['attempt_id'], $subject['id']]
    );
    $answeredSubjectQuestions = $answeredSubjectQuestionsStatement->fetch()['count'];
    
    $subjectStats[$subject['id']] = [
        'name' => $subject['name'],
        'total' => $totalSubjectQuestions,
        'answered' => $answeredSubjectQuestions,
        'unanswered' => $totalSubjectQuestions - $answeredSubjectQuestions
    ];
}

$title = "Exam Review - ECAT System";
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .exam-header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .exam-title {
            margin: 0;
            font-size: 20px;
        }
        
        .timer {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .review-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .review-title {
            margin: 0;
            color: #333;
        }
        
        .review-actions {
            display: flex;
            gap: 10px;
        }
        
        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        
        .button-return {
            background-color: #f1f1f1;
            color: #333;
        }
        
        .button-return:hover {
            background-color: #e0e0e0;
        }
        
        .button-submit {
            background-color: #ff9800;
            color: white;
        }
        
        .button-submit:hover {
            background-color: #f57c00;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background-color: #f9f9f9;
            border-radius: 4px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .stat-label {
            color: #666;
            font-size: 14px;
        }
        
        .stat-card.danger .stat-value {
            color: #f44336;
        }
        
        .stat-card.success .stat-value {
            color: #4CAF50;
        }
        
        .subjects-container {
            margin-bottom: 30px;
        }
        
        .subject-card {
            background-color: #f9f9f9;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .subject-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .subject-name {
            font-weight: bold;
            font-size: 18px;
        }
        
        .subject-stats {
            color: #666;
        }
        
        .progress-bar {
            height: 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #4CAF50;
        }
        
        .warning-container {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .warning-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .confirmation-checkbox {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="exam-header">
            <h1 class="exam-title">ECAT Examination Review</h1>
            <div class="timer" id="countdown">
                Time Remaining: <span id="hours"><?= floor($timeRemaining / 3600) ?></span>:<span id="minutes"><?= str_pad(floor(($timeRemaining % 3600) / 60), 2, '0', STR_PAD_LEFT) ?></span>:<span id="seconds"><?= str_pad($timeRemaining % 60, 2, '0', STR_PAD_LEFT) ?></span>
            </div>
        </div>
        
        <div class="review-container">
            <div class="review-header">
                <h2 class="review-title">Exam Summary</h2>
                <div class="review-actions">
                    <a href="/student/exam.php?subject=<?= $allSubjects[0]['id'] ?? 0 ?>&question=0" class="button button-return">Return to Exam</a>
                    <button id="submitButton" class="button button-submit" <?= $unansweredQuestions > 0 ? 'disabled' : '' ?>>Submit Exam</button>
                </div>
            </div>
            
            <div class="stats-container">
                <div class="stat-card success">
                    <div class="stat-value"><?= $answeredQuestions ?></div>
                    <div class="stat-label">Questions Answered</div>
                </div>
                
                <div class="stat-card <?= $unansweredQuestions > 0 ? 'danger' : '' ?>">
                    <div class="stat-value"><?= $unansweredQuestions ?></div>
                    <div class="stat-label">Questions Unanswered</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?= $totalQuestions ?></div>
                    <div class="stat-label">Total Questions</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-value"><?= floor($timeRemaining / 60) ?> min</div>
                    <div class="stat-label">Time Remaining</div>
                </div>
            </div>
            
            <?php if ($unansweredQuestions > 0): ?>
                <div class="warning-container">
                    <div class="warning-title">⚠️ You have unanswered questions!</div>
                    <p>You have <?= $unansweredQuestions ?> questions that you haven't answered yet. It's recommended to answer all questions before submitting your exam.</p>
                    <p>Use the subject breakdown below to navigate back to the unanswered questions.</p>
                </div>
            <?php endif; ?>
            
            <div class="subjects-container">
                <h3>Subject Breakdown</h3>
                
                <?php foreach ($subjectStats as $subjectId => $stats): ?>
                    <div class="subject-card">
                        <div class="subject-header">
                            <div class="subject-name"><?= htmlspecialchars($stats['name']) ?></div>
                            <div class="subject-stats">
                                <?= $stats['answered'] ?> / <?= $stats['total'] ?> questions answered
                                <?php if ($stats['unanswered'] > 0): ?>
                                    <span style="color: #f44336;">(<?= $stats['unanswered'] ?> unanswered)</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= ($stats['answered'] / max(1, $stats['total'])) * 100 ?>%;"></div>
                        </div>
                        <?php if ($stats['unanswered'] > 0): ?>
                            <div style="margin-top: 10px; text-align: right;">
                                <a href="/student/exam.php?subject=<?= $subjectId ?>&question=0" class="button button-return" style="padding: 5px 10px; font-size: 14px;">
                                    Review Questions
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <form id="submitForm" method="post" action="">
                <input type="hidden" name="submit_exam" value="1">
                <div class="confirmation-checkbox">
                    <input type="checkbox" id="confirmSubmission" onchange="toggleSubmitButton()">
                    <label for="confirmSubmission">I understand that once I submit the exam, I cannot change my answers. I am ready to submit my answers.</label>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Countdown timer
        let totalSeconds = <?= $timeRemaining ?>;
        const countdownElem = document.getElementById('countdown');
        const hoursElem = document.getElementById('hours');
        const minutesElem = document.getElementById('minutes');
        const secondsElem = document.getElementById('seconds');
        
        function updateCountdown() {
            if (totalSeconds <= 0) {
                // Time's up, submit the exam
                countdownElem.innerHTML = '<span style="color: #ffcdd2;">Time\'s Up! Submitting exam...</span>';
                document.getElementById('submitForm').submit();
                return;
            }
            
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            
            hoursElem.textContent = hours;
            minutesElem.textContent = minutes.toString().padStart(2, '0');
            secondsElem.textContent = seconds.toString().padStart(2, '0');
            
            // Change color when less than 10 minutes remain
            if (totalSeconds < 600) {
                countdownElem.style.backgroundColor = '#f44336';
            }
            
            totalSeconds--;
        }
        
        // Update countdown every second
        setInterval(updateCountdown, 1000);
        
        // Toggle submit button based on checkbox
        function toggleSubmitButton() {
            const checkbox = document.getElementById('confirmSubmission');
            const submitButton = document.getElementById('submitButton');
            
            // Only enable if checkbox is checked AND there are no unanswered questions (or override due to checkbox)
            <?php if ($unansweredQuestions > 0): ?>
                submitButton.disabled = !checkbox.checked;
            <?php else: ?>
                submitButton.disabled = !checkbox.checked;
            <?php endif; ?>
        }
        
        // Submit exam when button is clicked
        document.getElementById('submitButton').addEventListener('click', function() {
            if (!this.disabled) {
                if (confirm('Are you sure you want to submit your exam? This action cannot be undone.')) {
                    document.getElementById('submitForm').submit();
                }
            }
        });
    </script>
</body>
</html>