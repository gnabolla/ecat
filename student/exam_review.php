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

        /* Passage container styling */
        .passage-container {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            max-height: 350px;
            overflow-y: auto;
            font-size: 16px;
            line-height: 1.6;
        }

        .passage-title {
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .passage-source {
            font-style: italic;
            color: #666;
            margin-top: 15px;
            font-size: 14px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        /* Highlighting for important passage elements */
        .passage-container strong,
        .passage-container em,
        .passage-container b {
            color: #4CAF50;
        }

        /* For smaller screens, adjust the passage */
        @media (max-width: 768px) {
            .passage-container {
                max-height: 200px;
                font-size: 14px;
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

                        <?php if (isset($isPassageBasedQuestion) && $isPassageBasedQuestion): ?>
                            <!-- Display reading passage for passage-based questions -->
                            <div class="passage-container">
                                <?= nl2br(htmlspecialchars($passageContent)) ?>
                            </div>
                        <?php endif; ?>

                        <div class="question-text">
                            <?= htmlspecialchars($currentQuestion['question_text']) ?>
                        </div>

                        <?php
                        // Get the saved answer (now it should be the actual text, not the choice identifier)
                        $selectedChoice = $studentAnswer['selected_choice'] ?? '';

                        // We need to determine which radio button to check based on the saved answer
                        $checkedChoice1 = $selectedChoice === $currentQuestion['choice1'] ? 'checked' : '';
                        $checkedChoice2 = $selectedChoice === $currentQuestion['choice2'] ? 'checked' : '';
                        $checkedChoice3 = $selectedChoice === $currentQuestion['choice3'] ? 'checked' : '';
                        $checkedChoice4 = $selectedChoice === $currentQuestion['choice4'] ? 'checked' : '';

                        // For abstract reasoning, it's a bit different
                        // For abstract reasoning, it's a bit different
                        if ($isAbstractReasoning) {
                            // Abstract reasoning uses pattern numbers directly
                            $checkedChoice1 = $selectedChoice === '1' ? 'checked' : '';
                            $checkedChoice2 = $selectedChoice === '2' ? 'checked' : '';
                            $checkedChoice3 = $selectedChoice === '3' ? 'checked' : '';
                            $checkedChoice4 = $selectedChoice === '4' ? 'checked' : '';
                            $checkedChoice5 = $selectedChoice === '5' ? 'checked' : '';
                            $checkedChoice6 = $selectedChoice === '6' ? 'checked' : '';
                            $checkedChoice7 = $selectedChoice === '7' ? 'checked' : '';
                            $checkedChoice8 = $selectedChoice === '8' ? 'checked' : '';
                        }
                        ?>

                        <?php if ($isAbstractReasoning): ?>
                            <!-- Abstract Reasoning with 8 choices (handled at application level) -->
                            <div class="choices choices-grid abstract-reasoning">
                                <?php for ($i = 1; $i <= 8; $i++):
                                    $checkedVar = "checkedChoice$i";
                                ?>
                                    <div class="choice-item">
                                        <input type="radio" id="pattern<?= $i ?>" name="selected_choice"
                                            value="<?= $i ?>"
                                            <?= $$checkedVar ?>>
                                        <label for="pattern<?= $i ?>" class="choice-label" data-choice-number="<?= $i ?>">
                                            Pattern <?= $i ?>
                                        </label>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        <?php else: ?>
                            <!-- Regular subjects with 4 choices -->
                            <div class="choices">
                                <div class="choice-item">
                                    <input type="radio" id="choice1" name="selected_choice"
                                        value="choice1"
                                        <?= $checkedChoice1 ?>>
                                    <label for="choice1" class="choice-label">
                                        <?= htmlspecialchars($currentQuestion['choice1']) ?>
                                    </label>
                                </div>
                                <div class="choice-item">
                                    <input type="radio" id="choice2" name="selected_choice"
                                        value="choice2"
                                        <?= $checkedChoice2 ?>>
                                    <label for="choice2" class="choice-label">
                                        <?= htmlspecialchars($currentQuestion['choice2']) ?>
                                    </label>
                                </div>
                                <div class="choice-item">
                                    <input type="radio" id="choice3" name="selected_choice"
                                        value="choice3"
                                        <?= $checkedChoice3 ?>>
                                    <label for="choice3" class="choice-label">
                                        <?= htmlspecialchars($currentQuestion['choice3']) ?>
                                    </label>
                                </div>
                                <div class="choice-item">
                                    <input type="radio" id="choice4" name="selected_choice"
                                        value="choice4"
                                        <?= $checkedChoice4 ?>>
                                    <label for="choice4" class="choice-label">
                                        <?= htmlspecialchars($currentQuestion['choice4']) ?>
                                    </label>
                                </div>
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