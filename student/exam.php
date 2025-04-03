<?php
// Student exam interface (student/exam.php)
require_once __DIR__ . '/../bootstrap.php';

// Set timezone to Asia/Manila (Philippines)
date_default_timezone_set('Asia/Manila');

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
$subjects = $subjectsStatement->fetchAll();

// Get current question from URL parameter or default to first question
$currentSubjectId = isset($_GET['subject']) ? (int)$_GET['subject'] : ($subjects[0]['id'] ?? 0);
$currentQuestionIndex = isset($_GET['question']) ? (int)$_GET['question'] : 0;

// Get questions for the current subject
$questionsStatement = $db->query(
    "SELECT id, question_text, choice1, choice2, choice3, choice4, image_path 
     FROM questions WHERE subject_id = ? ORDER BY id",
    [$currentSubjectId]
);
$questions = $questionsStatement->fetchAll();

// If no questions found, try the first subject
if (empty($questions) && !empty($subjects)) {
    $currentSubjectId = $subjects[0]['id'];
    $questionsStatement = $db->query(
        "SELECT id, question_text, choice1, choice2, choice3, choice4, image_path 
         FROM questions WHERE subject_id = ? ORDER BY id",
        [$currentSubjectId]
    );
    $questions = $questionsStatement->fetchAll();
}

// Get the current question
$currentQuestion = $questions[$currentQuestionIndex] ?? null;

// Get student's answer for this question, if any
$studentAnswerStatement = null;
if ($currentQuestion) {
    $studentAnswerStatement = $db->query(
        "SELECT selected_choice FROM student_answers 
         WHERE attempt_id = ? AND question_id = ?",
        [$attempt['attempt_id'], $currentQuestion['id']]
    );
    $studentAnswer = $studentAnswerStatement->fetch();
}

// Process answer submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_answer'])) {
    $questionId = (int)$_POST['question_id'];
    $selectedChoice = $_POST['selected_choice'] ?? null;
    
    if ($selectedChoice) {
        // Check if answer already exists
        $checkAnswerStatement = $db->query(
            "SELECT answer_id FROM student_answers 
             WHERE attempt_id = ? AND question_id = ?",
            [$attempt['attempt_id'], $questionId]
        );
        $existingAnswer = $checkAnswerStatement->fetch();
        
        if ($existingAnswer) {
            // Update existing answer
            $db->query(
                "UPDATE student_answers 
                 SET selected_choice = ?, answered_at = NOW() 
                 WHERE attempt_id = ? AND question_id = ?",
                [$selectedChoice, $attempt['attempt_id'], $questionId]
            );
        } else {
            // Insert new answer
            $db->query(
                "INSERT INTO student_answers 
                 (attempt_id, question_id, selected_choice, answered_at) 
                 VALUES (?, ?, ?, NOW())",
                [$attempt['attempt_id'], $questionId, $selectedChoice]
            );
        }
    }
    
    // Determine where to go next
    $nextAction = $_POST['next_action'] ?? 'next';
    
    if ($nextAction === 'next') {
        // Go to next question
        if ($currentQuestionIndex < count($questions) - 1) {
            // Next question in current subject
            redirect("/student/exam.php?subject=$currentSubjectId&question=" . ($currentQuestionIndex + 1));
        } else {
            // Find next subject
            $nextSubjectFound = false;
            foreach ($subjects as $index => $subject) {
                if ($subject['id'] == $currentSubjectId && isset($subjects[$index + 1])) {
                    $nextSubjectId = $subjects[$index + 1]['id'];
                    redirect("/student/exam.php?subject=$nextSubjectId&question=0");
                    $nextSubjectFound = true;
                    break;
                }
            }
            
            if (!$nextSubjectFound) {
                // No next subject, go to review page
                redirect("/student/exam_review.php");
            }
        }
    } elseif ($nextAction === 'prev') {
        // Go to previous question
        if ($currentQuestionIndex > 0) {
            // Previous question in current subject
            redirect("/student/exam.php?subject=$currentSubjectId&question=" . ($currentQuestionIndex - 1));
        } else {
            // Find previous subject
            $prevSubjectFound = false;
            foreach ($subjects as $index => $subject) {
                if ($subject['id'] == $currentSubjectId && $index > 0) {
                    $prevSubjectId = $subjects[$index - 1]['id'];
                    
                    // Get count of questions in previous subject
                    $prevQuestionsStatement = $db->query(
                        "SELECT COUNT(*) as count FROM questions WHERE subject_id = ?",
                        [$prevSubjectId]
                    );
                    $prevQuestionCount = $prevQuestionsStatement->fetch()['count'];
                    
                    redirect("/student/exam.php?subject=$prevSubjectId&question=" . ($prevQuestionCount - 1));
                    $prevSubjectFound = true;
                    break;
                }
            }
            
            if (!$prevSubjectFound) {
                // No previous subject, stay on current question
                redirect("/student/exam.php?subject=$currentSubjectId&question=$currentQuestionIndex");
            }
        }
    } elseif ($nextAction === 'submit') {
        // Submit the exam
        
        // Update answers with correct/incorrect status
        $db->query(
            "UPDATE student_answers sa
             JOIN questions q ON sa.question_id = q.id
             SET sa.is_correct = CASE
                WHEN sa.selected_choice = 'choice1' AND q.correct_answer = q.choice1 THEN 1
                WHEN sa.selected_choice = 'choice2' AND q.correct_answer = q.choice2 THEN 1
                WHEN sa.selected_choice = 'choice3' AND q.correct_answer = q.choice3 THEN 1
                WHEN sa.selected_choice = 'choice4' AND q.correct_answer = q.choice4 THEN 1
                ELSE 0
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
        foreach ($subjects as $subject) {
            $subjectScoreStatement = $db->query(
                "SELECT 
                    COUNT(*) as items_attempted,
                    SUM(is_correct) as items_correct
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
    } else {
        // Just stay on the current question (review mode)
        redirect("/student/exam.php?subject=$currentSubjectId&question=$currentQuestionIndex");
    }
}

// Get subject name 
$subjectName = '';
foreach ($subjects as $subject) {
    if ($subject['id'] == $currentSubjectId) {
        $subjectName = $subject['name'];
        break;
    }
}

// Calculate progress
$totalQuestionsStatement = $db->query("SELECT COUNT(*) as count FROM questions");
$totalQuestions = $totalQuestionsStatement->fetch()['count'];

$answeredQuestionsStatement = $db->query(
    "SELECT COUNT(*) as count FROM student_answers WHERE attempt_id = ?",
    [$attempt['attempt_id']]
);
$answeredQuestions = $answeredQuestionsStatement->fetch()['count'];

$progressPercentage = $totalQuestions > 0 ? ($answeredQuestions / $totalQuestions * 100) : 0;

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
        
        .exam-container {
            display: flex;
            gap: 20px;
        }
        
        .subject-nav {
            flex: 0 0 250px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        
        .subject-nav h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .subject-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .subject-list li {
            margin-bottom: 10px;
        }
        
        .subject-list a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .subject-list a:hover, .subject-list a.active {
            background-color: #f0f0f0;
        }
        
        .subject-list a.active {
            font-weight: bold;
            background-color: #e8f5e9;
            color: #2e7d32;
        }
        
        .question-container {
            flex: 1;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        
        .question-nav {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .question-info {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }
        
        .question-text {
            font-size: 18px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .choices {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .choice-item {
            margin-bottom: 15px;
        }
        
        .choice-label {
            display: flex;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .choice-label:hover {
            background-color: #f9f9f9;
        }
        
        input[type="radio"] {
            margin-right: 15px;
        }
        
        input[type="radio"]:checked + .choice-label {
            background-color: #e8f5e9;
            border-color: #4CAF50;
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .button {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .button-prev {
            background-color: #f1f1f1;
            color: #333;
        }
        
        .button-prev:hover {
            background-color: #e0e0e0;
        }
        
        .button-next {
            background-color: #4CAF50;
            color: white;
        }
        
        .button-next:hover {
            background-color: #45a049;
        }
        
        .button-submit {
            background-color: #ff9800;
            color: white;
        }
        
        .button-submit:hover {
            background-color: #f57c00;
        }
        
        .progress-container {
            margin-top: 20px;
            padding: 10px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
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
        
        .question-image {
            max-width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .exam-container {
                flex-direction: column;
            }
            
            .subject-nav {
                flex: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="exam-header">
            <h1 class="exam-title">ECAT Examination</h1>
            <div class="timer" id="countdown">
                Time Remaining: <span id="hours"><?= floor($timeRemaining / 3600) ?></span>:<span id="minutes"><?= str_pad(floor(($timeRemaining % 3600) / 60), 2, '0', STR_PAD_LEFT) ?></span>:<span id="seconds"><?= str_pad($timeRemaining % 60, 2, '0', STR_PAD_LEFT) ?></span>
            </div>
        </div>
        
        <?php if (!$currentQuestion): ?>
            <div class="question-container">
                <h2>No questions found</h2>
                <p>There are no questions available for this examination. Please contact the administrator.</p>
                <a href="/student/dashboard.php" class="button button-next">Return to Dashboard</a>
            </div>
        <?php else: ?>
            <div class="exam-container">
                <div class="subject-nav">
                    <h3>Subjects</h3>
                    <ul class="subject-list">
                        <?php foreach ($subjects as $subject): ?>
                            <li>
                                <a href="/student/exam.php?subject=<?= $subject['id'] ?>&question=0" 
                                   class="<?= $subject['id'] == $currentSubjectId ? 'active' : '' ?>">
                                    <?= htmlspecialchars($subject['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <div class="progress-container">
                        <div class="progress-info">
                            <span>Progress:</span>
                            <span><?= $answeredQuestions ?> / <?= $totalQuestions ?> questions</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= number_format($progressPercentage, 2) ?>%;"></div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 20px; text-align: center;">
                        <form method="post" action="">
                            <input type="hidden" name="question_id" value="<?= $currentQuestion['id'] ?>">
                            <input type="hidden" name="next_action" value="submit">
                            <button type="submit" name="submit_answer" class="button button-submit" style="width: 100%;"
                                    onclick="return confirm('Are you sure you want to submit your exam? You cannot change your answers after submission.')">
                                Submit Exam
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="question-container">
                    <div class="question-nav">
                        <div class="question-info">
                            Subject: <?= htmlspecialchars($subjectName) ?> | 
                            Question <?= $currentQuestionIndex + 1 ?> of <?= count($questions) ?>
                        </div>
                    </div>
                    
                    <form method="post" action="" id="questionForm">
                        <input type="hidden" name="question_id" value="<?= $currentQuestion['id'] ?>">
                        
                        <?php if ($currentQuestion['image_path']): ?>
                            <img src="<?= htmlspecialchars($currentQuestion['image_path']) ?>" alt="Question Image" class="question-image">
                        <?php endif; ?>
                        
                        <div class="question-text">
                            <?= htmlspecialchars($currentQuestion['question_text']) ?>
                        </div>
                        
                        <div class="choices">
                            <?php
                            $selectedChoice = $studentAnswer['selected_choice'] ?? '';
                            for ($i = 1; $i <= 4; $i++): 
                                $choiceKey = "choice$i";
                            ?>
                                <div class="choice-item">
                                    <input type="radio" id="<?= $choiceKey ?>" name="selected_choice" 
                                           value="<?= $choiceKey ?>" 
                                           <?= $selectedChoice === $choiceKey ? 'checked' : '' ?>>
                                    <label for="<?= $choiceKey ?>" class="choice-label">
                                        <?= htmlspecialchars($currentQuestion[$choiceKey]) ?>
                                    </label>
                                </div>
                            <?php endfor; ?>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="submit" name="submit_answer" class="button button-prev" 
                                    <?= $currentQuestionIndex == 0 && array_search($currentSubjectId, array_column($subjects, 'id')) === 0 ? 'disabled' : '' ?>
                                    onclick="document.getElementById('questionForm').elements.namedItem('next_action').value = 'prev'">
                                Previous Question
                            </button>
                            
                            <input type="hidden" name="next_action" value="next">
                            
                            <button type="submit" name="submit_answer" class="button button-next">
                                <?php 
                                // Check if this is the last question of the last subject
                                $isLastQuestion = $currentQuestionIndex == count($questions) - 1;
                                $isLastSubject = array_search($currentSubjectId, array_column($subjects, 'id')) === count($subjects) - 1;
                                
                                if ($isLastQuestion && $isLastSubject): 
                                    echo 'Review Answers';
                                else: 
                                    echo 'Next Question';
                                endif; 
                                ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
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
                document.querySelector('.button-submit').click();
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
        
        // Form submission confirmation
        document.querySelector('.button-submit').addEventListener('click', function(e) {
            const confirmed = confirm('Are you sure you want to submit your exam? You cannot change your answers after submission.');
            if (!confirmed) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>