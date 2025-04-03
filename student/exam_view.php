<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/assets/css/exam.css">
    <style>
        /* Additional styles for Abstract Reasoning */
        .choices-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        /* Override label content for Abstract Reasoning */
        .abstract-reasoning .choice-label::before {
            content: attr(data-choice-number) " ";
            font-weight: bold;
            margin-right: 5px;
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
                            <button type="submit" name="submit_answer" class="button button-submit" style="width: 100%;">
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
                        
                        <?php if ($imageExists): ?>
                            <img src="<?= htmlspecialchars($imagePath) ?>" alt="Question Image" class="question-image">
                        <?php endif; ?>
                        
                        <div class="question-text">
                            <?= htmlspecialchars($currentQuestion['question_text']) ?>
                        </div>
                        
                        <?php $selectedChoice = $studentAnswer['selected_choice'] ?? ''; ?>
                        
                        <?php if ($isAbstractReasoning): ?>
                            <!-- Abstract Reasoning with 8 choices (handled at application level) -->
                            <div class="choices choices-grid abstract-reasoning">
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <div class="choice-item">
                                        <input type="radio" id="pattern<?= $i ?>" name="selected_choice" 
                                               value="<?= $i ?>" 
                                               <?= $selectedChoice == $i ? 'checked' : '' ?>>
                                        <label for="pattern<?= $i ?>" class="choice-label" data-choice-number="<?= $i ?>">
                                            Pattern <?= $i ?>
                                        </label>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        <?php else: ?>
                            <!-- Regular subjects with 4 choices -->
                            <div class="choices">
                                <?php for ($i = 1; $i <= 4; $i++): 
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
                        <?php endif; ?>
                        
                        <div class="action-buttons">
                            <button type="submit" name="submit_answer" class="button button-prev" 
                                    <?= $currentQuestionIndex == 0 && array_search($currentSubjectId, array_column($subjects, 'id')) === 0 ? 'disabled' : '' ?>>
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
    
    <script src="/assets/js/exam.js"></script>
    <script>
        // Initialize the timer with the remaining time
        document.addEventListener('DOMContentLoaded', function() {
            initializeTimer(<?= $timeRemaining ?>);
        });
    </script>
</body>
</html>