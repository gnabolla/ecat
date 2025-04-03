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

// Get subjects and scores
$subjectScoresStatement = $db->query(
    "SELECT s.id, s.name, asbs.score, asbs.items_attempted, asbs.items_correct
     FROM subjects s
     LEFT JOIN attempt_scores_by_subject asbs ON s.id = asbs.subject_id AND asbs.attempt_id = ?
     ORDER BY s.name",
    [$attempt['attempt_id']]
);
$subjectScores = $subjectScoresStatement->fetchAll();

// Calculate total questions, answered questions, and overall score
$totalQuestionsStatement = $db->query("SELECT COUNT(*) as count FROM questions");
$totalQuestions = $totalQuestionsStatement->fetch()['count'];

$answeredQuestionsStatement = $db->query(
    "SELECT COUNT(*) as count FROM student_answers WHERE attempt_id = ?",
    [$attempt['attempt_id']]
);
$answeredQuestions = $answeredQuestionsStatement->fetch()['count'];

$correctAnswersStatement = $db->query(
    "SELECT COUNT(*) as count FROM student_answers WHERE attempt_id = ? AND is_correct = 1",
    [$attempt['attempt_id']]
);
$correctAnswers = $correctAnswersStatement->fetch()['count'];

// Calculate percentages
$completionRate = $totalQuestions > 0 ? ($answeredQuestions / $totalQuestions) * 100 : 0;
$overallScore = $answeredQuestions > 0 ? ($correctAnswers / $answeredQuestions) * 100 : 0;

// Calculate test duration
$startTime = strtotime($attempt['start_time']);
$endTime = strtotime($attempt['end_time']);
$duration = $endTime - $startTime;
$hours = floor($duration / 3600);
$minutes = floor(($duration % 3600) / 60);
$seconds = $duration % 60;

$title = "Exam Results - ECAT System";
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
        
        .results-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .results-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .results-title {
            color: #333;
            margin-top: 0;
            margin-bottom: 10px;
        }
        
        .results-subtitle {
            color: #666;
            margin: 0;
            font-size: 16px;
        }
        
        .result-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .status-completed {
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-expired {
            background-color: #ffebee;
            color: #c62828;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .summary-card.score {
            background-color: #e8f5e9;
        }
        
        .summary-value {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .summary-label {
            color: #666;
            font-size: 14px;
        }
        
        .subjects-container {
            margin-bottom: 30px;
        }
        
        .subjects-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .subjects-title {
            margin: 0;
            color: #333;
        }
        
        .subject-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .subject-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            align-items: center;
        }
        
        .subject-name {
            font-weight: bold;
            font-size: 18px;
        }
        
        .subject-score {
            font-size: 18px;
            font-weight: bold;
        }
        
        .progress-container {
            margin-bottom: 10px;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
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
        
        .progress-fill.low {
            background-color: #f44336;
        }
        
        .progress-fill.medium {
            background-color: #ff9800;
        }
        
        .progress-fill.high {
            background-color: #4CAF50;
        }
        
        .subject-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }
        
        .print-button {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        
        .print-button:hover {
            background-color: #0b7dda;
        }
        
        @media print {
            body {
                background-color: white;
            }
            
            .container {
                max-width: 100%;
                padding: 0;
            }
            
            header, .back-link, .print-button {
                display: none;
            }
            
            .results-card {
                box-shadow: none;
                margin: 0;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1 class="header-title">Exam Results</h1>
            <div class="back-link">
                <a href="/student/dashboard.php">Back to Dashboard</a>
            </div>
        </header>
        
        <?php flashMessage(); ?>
        
        <div class="results-card">
            <div class="results-header">
                <h2 class="results-title">ECAT Examination Results</h2>
                <p class="results-subtitle">
                    Completed on: <?= date('F d, Y h:i A', strtotime($attempt['end_time'])) ?>
                    <span class="result-status <?= $attempt['status'] === 'Completed' ? 'status-completed' : 'status-expired' ?>">
                        <?= $attempt['status'] ?>
                    </span>
                </p>
            </div>
            
            <div class="summary-grid">
                <div class="summary-card score">
                    <div class="summary-value"><?= number_format($overallScore, 1) ?>%</div>
                    <div class="summary-label">Overall Score</div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-value"><?= $correctAnswers ?> / <?= $answeredQuestions ?></div>
                    <div class="summary-label">Correct Answers</div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-value"><?= $answeredQuestions ?> / <?= $totalQuestions ?></div>
                    <div class="summary-label">Questions Answered</div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-value">
                        <?= $hours > 0 ? $hours . 'h ' : '' ?><?= $minutes ?>m <?= $seconds ?>s
                    </div>
                    <div class="summary-label">Exam Duration</div>
                </div>
            </div>
            
            <div class="subjects-container">
                <div class="subjects-header">
                    <h3 class="subjects-title">Scores by Subject</h3>
                    <button onclick="window.print()" class="print-button">Print Results</button>
                </div>
                
                <?php foreach ($subjectScores as $subject): ?>
                    <?php
                    // Calculate subject score percentage
                    $scorePercentage = 0;
                    if (isset($subject['items_attempted']) && $subject['items_attempted'] > 0) {
                        $scorePercentage = ($subject['items_correct'] / $subject['items_attempted']) * 100;
                    }
                    
                    // Determine color class based on score
                    $colorClass = 'low';
                    if ($scorePercentage >= 70) {
                        $colorClass = 'high';
                    } elseif ($scorePercentage >= 50) {
                        $colorClass = 'medium';
                    }
                    ?>
                    
                    <div class="subject-card">
                        <div class="subject-header">
                            <div class="subject-name"><?= htmlspecialchars($subject['name']) ?></div>
                            <div class="subject-score">
                                <?= $subject['items_correct'] ?? 0 ?> / <?= $subject['items_attempted'] ?? 0 ?> 
                                (<?= number_format($scorePercentage, 1) ?>%)
                            </div>
                        </div>
                        
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill <?= $colorClass ?>" style="width: <?= $scorePercentage ?>%;"></div>
                            </div>
                        </div>
                        
                        <div class="subject-stats">
                            <div>Attempted: <?= $subject['items_attempted'] ?? 0 ?> questions</div>
                            <div>Correct: <?= $subject['items_correct'] ?? 0 ?> questions</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>