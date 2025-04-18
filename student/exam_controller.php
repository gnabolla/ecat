<?php
// Student exam controller (student/exam_controller.php)
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
$subjectsStatement = $db->query("SELECT id, name FROM subjects");
$allSubjects = $subjectsStatement->fetchAll();

// Create a map of subject IDs to their details for easy lookup
$subjectMap = [];
foreach ($allSubjects as $subject) {
    $subjectMap[$subject['id']] = $subject;
}

// Use the subject order stored in session if available
// This ensures subjects remain in the same order throughout the exam
$subjects = [];
if (isset($_SESSION['exam_subjects_order']) && is_array($_SESSION['exam_subjects_order'])) {
    foreach ($_SESSION['exam_subjects_order'] as $subjectId) {
        if (isset($subjectMap[$subjectId])) {
            $subjects[] = $subjectMap[$subjectId];
        }
    }
}

// If no stored order or empty subjects array, fall back to database order
if (empty($subjects)) {
    $subjects = $allSubjects;
}

// If no subjects found, handle error
if (empty($subjects)) {
    redirect('/student/test.php', 'No subjects found for the exam. Please contact the administrator.', 'error');
}

// Get current question from URL parameter or default to first question
$currentSubjectId = isset($_GET['subject']) ? (int)$_GET['subject'] : ($subjects[0]['id'] ?? 0);
$currentQuestionIndex = isset($_GET['question']) ? (int)$_GET['question'] : 0;

// Check if current subject is Abstract Reasoning
$isAbstractReasoning = false;
foreach ($subjects as $subject) {
    if ($subject['id'] == $currentSubjectId && stripos($subject['name'], 'abstract reasoning') !== false) {
        $isAbstractReasoning = true;
        break;
    }
}

// Get questions for the current subject
// Use the question order stored in session if available
$questions = [];
if (isset($_SESSION['exam_questions_order'][$currentSubjectId]) && 
    is_array($_SESSION['exam_questions_order'][$currentSubjectId])) {
    
    $questionIds = $_SESSION['exam_questions_order'][$currentSubjectId];
    $placeholders = implode(',', array_fill(0, count($questionIds), '?'));
    
    if (!empty($questionIds)) {
        $questionsStatement = $db->query(
            "SELECT id, question_text, choice1, choice2, choice3, choice4, image_path 
             FROM questions 
             WHERE id IN ($placeholders)
             ORDER BY FIELD(id, $placeholders)",
            array_merge($questionIds, $questionIds)
        );
        $questions = $questionsStatement->fetchAll();
    }
}

// If no stored questions or empty questions array, fall back to database order
if (empty($questions)) {
    $questionsStatement = $db->query(
        "SELECT id, question_text, choice1, choice2, choice3, choice4, image_path 
         FROM questions WHERE subject_id = ? ORDER BY id",
        [$currentSubjectId]
    );
    $questions = $questionsStatement->fetchAll();
}

// Keep passage-based questions together - THIS IS NEW CODE
// We're looking for questions with specific IDs (13, 14, 15) that should be kept together
// Check if the current question is one of the passage-based questions (IDs 13, 14, 15)
$passageBasedQuestionIds = [13, 14, 15];
$isPassageBasedQuestion = false;
$passageContent = '';

// Check if current question is a passage-based question
$currentQuestion = $questions[$currentQuestionIndex] ?? null;
if ($currentQuestion && in_array($currentQuestion['id'], $passageBasedQuestionIds)) {
    $isPassageBasedQuestion = true;
    
    // Retrieve the passage content - stored as a common text for questions 13-15
    $passageContent = "Directions: Read the passages below and answer the questions that follow. Passage 1 Misconduct such as cheating and dishonesty is becoming a perennial problem in schools. In a study conducted on April 29, 2004, ABC's (American Broadcasting Corporation), Prime Time had a segment on cheating of students in the education system. They tackled the issue of cheating in colleges and high schools. They found that 75% of students admitted to cheating on an exam or paper. Of the 12,000 college students, 75% admitted that they have cheated on an exam or term project (Mujtaba and Preziosi, 2006). Students are using calculators, cell phones, computers, and other devices to store and/or download relevant information to complete the exam. One student was timed by Charlie Gibson to see how long it took her to get the answer for one of the questions from another student using her cell phone's text messaging function. It took her less than 30 seconds, using one hand under table, to ask the question and receive the answer while the other hand seemed to be attempting to take the test. Some students feel that they need to cheat since their counterparts are doing it. Others feel that they need to cheat as the school system is simply a \"dress rehearsal\" for the \"cut throat\" world of business. Many students feel that if senior business officers or religious leaders cheat and politicians, including governors and presidents, lie, then they, too, have the right to cheat and get ahead using tactics available to them. To minimize the incidents of cheating, the writers suggest that teachers should involve their students in the learning process. Teachers can provide learning activities that meet the learning needs and reinforce learning in the daily activities of the students. In this case, students are evaluated according to their performance and come up with honest outputs. Source: Cavico, F. and Mujtaba, B. (2009). Making The Case For The Creation Of An Academic Honesty And Integrity Culture In Higher Education: Reflections And Suggestions For Reducing The Rise In Student Cheating. American Journal of Business Education, 2(5), pp. 75-88.";
}

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
$studentAnswer = null;
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
        // For Abstract Reasoning, store the selected pattern number directly
        if ($isAbstractReasoning) {
            $selectedAnswerText = $selectedChoice; // Pattern number for abstract reasoning
        } else {
            // For regular questions, get the actual answer text based on choice selected
            $questionStatement = $db->query(
                "SELECT " . $selectedChoice . " AS selected_answer_text FROM questions WHERE id = ?", 
                [$questionId]
            );
            $questionData = $questionStatement->fetch();
            $selectedAnswerText = $questionData['selected_answer_text'] ?? '';
        }

        // Check if answer already exists
        $checkAnswerStatement = $db->query(
            "SELECT answer_id FROM student_answers 
             WHERE attempt_id = ? AND question_id = ?",
            [$attempt['attempt_id'], $questionId]
        );
        $existingAnswer = $checkAnswerStatement->fetch();

        if ($existingAnswer) {
            // Update existing answer with the actual answer text
            $db->query(
                "UPDATE student_answers 
                 SET selected_choice = ?, answered_at = NOW() 
                 WHERE attempt_id = ? AND question_id = ?",
                [$selectedAnswerText, $attempt['attempt_id'], $questionId]
            );
        } else {
            // Insert new answer with the actual answer text
            $db->query(
                "INSERT INTO student_answers 
                 (attempt_id, question_id, selected_choice, answered_at) 
                 VALUES (?, ?, ?, NOW())",
                [$attempt['attempt_id'], $questionId, $selectedAnswerText]
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
                WHEN sa.selected_choice = q.correct_answer THEN 1
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

// Check if image exists for the current question
$imagePath = '';
$imageExists = false;
if ($currentQuestion && !empty($currentQuestion['image_path'])) {
    $imagePath = '/assets/img/' . basename($currentQuestion['image_path']);
    $fullImagePath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;
    $imageExists = file_exists($fullImagePath);
}

// Include the view file
require_once 'exam_view.php';