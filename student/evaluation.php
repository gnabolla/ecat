<?php
// File: student/evaluation.php

require_once __DIR__ . '/../bootstrap.php';
requireStudentLogin(); // Ensure student is logged in

$student_id = $_SESSION['student_id'];
$errors = []; // Array to hold validation errors
$submitted_data = []; // Array to hold submitted data on POST error

// --- Get and Validate attempt_id ---
$attempt_id = $_GET['attempt_id'] ?? $_POST['attempt_id'] ?? null; // Get from GET or POST

if (!$attempt_id || !filter_var($attempt_id, FILTER_VALIDATE_INT)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Invalid attempt specified.'];
    header('Location: /student/dashboard.php');
    exit;
}

// --- Verify Attempt belongs to the logged-in student and is completed/expired ---
try {
    $verifyStmt = $db->query(
        "SELECT status FROM test_attempts WHERE attempt_id = :attempt_id AND student_id = :student_id AND status IN ('Completed', 'Expired')",
        ['attempt_id' => $attempt_id, 'student_id' => $student_id]
    );
    $attempt_status = $verifyStmt->fetchColumn(); // Fetch only the status column

    if (!$attempt_status) {
        // Attempt not found, doesn't belong to student, or not finished
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Invalid or inaccessible exam attempt specified.'];
        header('Location: /student/dashboard.php');
        exit;
    }

    // --- Check if evaluation already submitted ---
     $evalCheckStmt = $db->query(
        "SELECT 1 FROM examinee_evaluations WHERE attempt_id = :attempt_id LIMIT 1",
        ['attempt_id' => $attempt_id]
    );
    if ($evalCheckStmt->fetch()) {
         $_SESSION['flash_message'] = ['type' => 'info', 'message' => 'You have already submitted the evaluation for this exam attempt.'];
         header('Location: /student/dashboard.php');
         exit;
    }

} catch (PDOException $e) {
    error_log("Error verifying attempt/evaluation status: " . $e->getMessage());
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Database error checking attempt status.'];
    header('Location: /student/dashboard.php');
    exit;
}


// --- Handle Form Submission (POST Request) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Store submitted data in case of errors
    $submitted_data = $_POST;

    // --- Basic Validation ---
    // List of required radio button question keys
    $required_radio_keys = [
        'q1_computer_easy', 'q2_system_functioned', 'q3_network_issues',
        'q4_instructions_clear', 'q5_review_change_easy', 'q6_layout_comfortable',
        'q7_system_responsiveness', 'q8_security_satisfaction', 'q9_navigation_problems',
        'q10_system_rating', 'q11_room_conducive', 'q12_equipment_comfortable',
        'q13_enough_time', 'q14_facilitator_instructions', 'q15_facilitator_helpful',
        'q16_environment_quiet', 'q17_issues_addressed', 'q18_prefer_computer_based',
        'q19_distractions'
    ];

    foreach ($required_radio_keys as $key) {
        if (empty($_POST[$key])) {
            // Extract question number for user-friendly message
            preg_match('/q(\d+)_/', $key, $matches);
            $q_num = $matches[1] ?? $key;
            $errors[$key] = "Question {$q_num} requires an answer.";
        }
    }

    // Note: q20_suggestions is optional based on schema (TEXT DEFAULT NULL)

    // --- If No Validation Errors, Insert into DB ---
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO examinee_evaluations (
                        attempt_id,
                        q1_computer_easy, q2_system_functioned, q3_network_issues, q4_instructions_clear,
                        q5_review_change_easy, q6_layout_comfortable, q7_system_responsiveness,
                        q8_security_satisfaction, q9_navigation_problems, q10_system_rating,
                        q11_room_conducive, q12_equipment_comfortable, q13_enough_time,
                        q14_facilitator_instructions, q15_facilitator_helpful, q16_environment_quiet,
                        q17_issues_addressed, q18_prefer_computer_based, q19_distractions,
                        q20_suggestions
                    ) VALUES (
                        :attempt_id,
                        :q1, :q2, :q3, :q4, :q5, :q6, :q7, :q8, :q9, :q10,
                        :q11, :q12, :q13, :q14, :q15, :q16, :q17, :q18, :q19,
                        :q20
                    )";

            $stmt = $db->connection->prepare($sql); // Access PDO connection directly if needed

            $stmt->execute([
                ':attempt_id' => $attempt_id,
                ':q1' => $_POST['q1_computer_easy'],
                ':q2' => $_POST['q2_system_functioned'],
                ':q3' => $_POST['q3_network_issues'],
                ':q4' => $_POST['q4_instructions_clear'],
                ':q5' => $_POST['q5_review_change_easy'],
                ':q6' => $_POST['q6_layout_comfortable'],
                ':q7' => $_POST['q7_system_responsiveness'],
                ':q8' => $_POST['q8_security_satisfaction'],
                ':q9' => $_POST['q9_navigation_problems'],
                ':q10' => $_POST['q10_system_rating'],
                ':q11' => $_POST['q11_room_conducive'],
                ':q12' => $_POST['q12_equipment_comfortable'],
                ':q13' => $_POST['q13_enough_time'],
                ':q14' => $_POST['q14_facilitator_instructions'],
                ':q15' => $_POST['q15_facilitator_helpful'],
                ':q16' => $_POST['q16_environment_quiet'],
                ':q17' => $_POST['q17_issues_addressed'],
                ':q18' => $_POST['q18_prefer_computer_based'],
                ':q19' => $_POST['q19_distractions'],
                ':q20' => $_POST['q20_suggestions'] ?? null // Handle optional suggestion
            ]);

            // --- Success ---
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Thank you! Your evaluation has been submitted successfully.'];
            header('Location: /student/dashboard.php'); // Redirect to dashboard
            exit;

        } catch (PDOException $e) {
            error_log("Database error inserting evaluation: " . $e->getMessage());
            // Check for unique constraint violation (double submission attempt)
            if ($e->getCode() == '23000') { // SQLSTATE for integrity constraint violation
                 $_SESSION['flash_message'] = ['type' => 'info', 'message' => 'You have already submitted the evaluation for this exam attempt.'];
                 header('Location: /student/dashboard.php');
                 exit;
            } else {
                 $errors['db_error'] = "A database error occurred. Please try again.";
            }
            // Let the script continue to redisplay the form with the error
        }

    } else {
        // Validation errors occurred
         $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Please answer all required questions.'];
         // The script will continue below to redisplay the form
    }
}

// --- Head ---
$pageTitle = "Examinee Evaluation";
require_once __DIR__ . '/../views/partials/head.php'; // Adjust path

// Helper to check radio button based on submitted data (used on validation errors)
function isChecked($key, $value, $submitted_data) {
    return isset($submitted_data[$key]) && $submitted_data[$key] === $value ? 'checked' : '';
}
?>
<style>
    /* Add some basic form styling */
    .evaluation-form { max-width: 800px; margin: 20px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .evaluation-form h1 { text-align: center; margin-bottom: 10px; }
    .evaluation-form .instructions { text-align: center; margin-bottom: 30px; color: #555; }
    .question-group { margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
    .question-group:last-of-type { border-bottom: none; }
    .question-group label.question-text { display: block; font-weight: bold; margin-bottom: 10px; color: #333; }
    .options-group label { margin-right: 20px; display: inline-block; cursor: pointer; }
    .options-group input[type="radio"] { margin-right: 5px; }
    .form-textarea { width: 100%; min-height: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; font-size: 1rem; box-sizing: border-box; }
    .submit-btn { display: block; width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 5px; font-size: 1.1rem; cursor: pointer; transition: background-color 0.2s ease; }
    .submit-btn:hover { background-color: #218838; }
    .error-message { color: #dc3545; font-size: 0.9em; margin-top: 5px; }
    .is-invalid { border-color: #dc3545 !important; } /* Highlight fields with errors */
    .required-asterisk { color: #dc3545; margin-left: 2px; }
</style>
<body>
    <div class="container">

        <form action="evaluation.php" method="POST" class="evaluation-form">
            <h1>Examinee Evaluation Form</h1>
            <p class="instructions">Please answer the following questions honestly. Your feedback will help us improve the admission test experience.</p>

            <?php flashMessage(); // Show flash messages (like validation errors) ?>
            <?php if (isset($errors['db_error'])): ?>
                <div class="alert alert-danger"><?= e($errors['db_error']) ?></div>
            <?php endif; ?>

             <!-- Add hidden field for attempt_id -->
             <input type="hidden" name="attempt_id" value="<?= e($attempt_id) ?>">

            <h2>System/Technology-Related Questions</h2>

            <div class="question-group">
                <label class="question-text" for="q1_computer_easy">1. Was the computer easy to use during the exam?<span class="required-asterisk">*</span></label>
                <div class="options-group">
                    <label><input type="radio" name="q1_computer_easy" value="Yes" <?= isChecked('q1_computer_easy', 'Yes', $submitted_data) ?> required> Yes</label>
                    <label><input type="radio" name="q1_computer_easy" value="No" <?= isChecked('q1_computer_easy', 'No', $submitted_data) ?> > No</label>
                    <label><input type="radio" name="q1_computer_easy" value="Somewhat" <?= isChecked('q1_computer_easy', 'Somewhat', $submitted_data) ?> > Somewhat</label>
                </div>
                <?php if(isset($errors['q1_computer_easy'])): ?><div class="error-message"><?= $errors['q1_computer_easy'] ?></div><?php endif; ?>
            </div>

             <div class="question-group">
                <label class="question-text" for="q2_system_functioned">2. Did the system function properly without any technical issues (e.g., freezing, crashing)?<span class="required-asterisk">*</span></label>
                <div class="options-group">
                    <label><input type="radio" name="q2_system_functioned" value="Yes" <?= isChecked('q2_system_functioned', 'Yes', $submitted_data) ?> required> Yes</label>
                    <label><input type="radio" name="q2_system_functioned" value="No" <?= isChecked('q2_system_functioned', 'No', $submitted_data) ?> > No</label>
                    <label><input type="radio" name="q2_system_functioned" value="Somewhat" <?= isChecked('q2_system_functioned', 'Somewhat', $submitted_data) ?> > Somewhat</label>
                </div>
                 <?php if(isset($errors['q2_system_functioned'])): ?><div class="error-message"><?= $errors['q2_system_functioned'] ?></div><?php endif; ?>
            </div>

            <div class="question-group">
                <label class="question-text" for="q3_network_issues">3. Did you experience any network or connectivity issues during the test?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                    <label><input type="radio" name="q3_network_issues" value="Yes" <?= isChecked('q3_network_issues', 'Yes', $submitted_data) ?> required> Yes</label>
                    <label><input type="radio" name="q3_network_issues" value="No" <?= isChecked('q3_network_issues', 'No', $submitted_data) ?>> No</label>
                    <label><input type="radio" name="q3_network_issues" value="Somewhat" <?= isChecked('q3_network_issues', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                </div>
                 <?php if(isset($errors['q3_network_issues'])): ?><div class="error-message"><?= $errors['q3_network_issues'] ?></div><?php endif; ?>
            </div>

             <div class="question-group">
                 <label class="question-text" for="q4_instructions_clear">4. Were the instructions on the screen clear and easy to understand?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                     <label><input type="radio" name="q4_instructions_clear" value="Yes" <?= isChecked('q4_instructions_clear', 'Yes', $submitted_data) ?> required> Yes</label>
                     <label><input type="radio" name="q4_instructions_clear" value="No" <?= isChecked('q4_instructions_clear', 'No', $submitted_data) ?>> No</label>
                     <label><input type="radio" name="q4_instructions_clear" value="Somewhat" <?= isChecked('q4_instructions_clear', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                 </div>
                  <?php if(isset($errors['q4_instructions_clear'])): ?><div class="error-message"><?= $errors['q4_instructions_clear'] ?></div><?php endif; ?>
             </div>

             <div class="question-group">
                 <label class="question-text" for="q5_review_change_easy">5. Did the system allow you to easily review and change your answers before submitting?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                      <label><input type="radio" name="q5_review_change_easy" value="Yes" <?= isChecked('q5_review_change_easy', 'Yes', $submitted_data) ?> required> Yes</label>
                      <label><input type="radio" name="q5_review_change_easy" value="No" <?= isChecked('q5_review_change_easy', 'No', $submitted_data) ?>> No</label>
                      <label><input type="radio" name="q5_review_change_easy" value="Somewhat" <?= isChecked('q5_review_change_easy', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                  </div>
                   <?php if(isset($errors['q5_review_change_easy'])): ?><div class="error-message"><?= $errors['q5_review_change_easy'] ?></div><?php endif; ?>
             </div>

             <div class="question-group">
                 <label class="question-text" for="q6_layout_comfortable">6. Were the fonts, colors, and screen layout comfortable for reading?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                     <label><input type="radio" name="q6_layout_comfortable" value="Yes" <?= isChecked('q6_layout_comfortable', 'Yes', $submitted_data) ?> required> Yes</label>
                     <label><input type="radio" name="q6_layout_comfortable" value="No" <?= isChecked('q6_layout_comfortable', 'No', $submitted_data) ?>> No</label>
                     <label><input type="radio" name="q6_layout_comfortable" value="Somewhat" <?= isChecked('q6_layout_comfortable', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                 </div>
                  <?php if(isset($errors['q6_layout_comfortable'])): ?><div class="error-message"><?= $errors['q6_layout_comfortable'] ?></div><?php endif; ?>
             </div>

            <div class="question-group">
                <label class="question-text" for="q7_system_responsiveness">7. How responsive was the system to your inputs (e.g., selecting answers, navigating questions)?<span class="required-asterisk">*</span></label>
                <div class="options-group">
                    <label><input type="radio" name="q7_system_responsiveness" value="Very responsive" <?= isChecked('q7_system_responsiveness', 'Very responsive', $submitted_data) ?> required> Very responsive</label>
                    <label><input type="radio" name="q7_system_responsiveness" value="Somewhat responsive" <?= isChecked('q7_system_responsiveness', 'Somewhat responsive', $submitted_data) ?>> Somewhat responsive</label>
                    <label><input type="radio" name="q7_system_responsiveness" value="Slow and unresponsive" <?= isChecked('q7_system_responsiveness', 'Slow and unresponsive', $submitted_data) ?>> Slow and unresponsive</label>
                </div>
                 <?php if(isset($errors['q7_system_responsiveness'])): ?><div class="error-message"><?= $errors['q7_system_responsiveness'] ?></div><?php endif; ?>
            </div>

             <div class="question-group">
                 <label class="question-text" for="q8_security_satisfaction">8. How satisfied are you with the security features of the computerized test?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                     <label><input type="radio" name="q8_security_satisfaction" value="Very satisfied" <?= isChecked('q8_security_satisfaction', 'Very satisfied', $submitted_data) ?> required> Very satisfied</label>
                     <label><input type="radio" name="q8_security_satisfaction" value="Satisfied" <?= isChecked('q8_security_satisfaction', 'Satisfied', $submitted_data) ?>> Satisfied</label>
                     <label><input type="radio" name="q8_security_satisfaction" value="Neutral" <?= isChecked('q8_security_satisfaction', 'Neutral', $submitted_data) ?>> Neutral</label>
                     <label><input type="radio" name="q8_security_satisfaction" value="Dissatisfied" <?= isChecked('q8_security_satisfaction', 'Dissatisfied', $submitted_data) ?>> Dissatisfied</label>
                     <label><input type="radio" name="q8_security_satisfaction" value="Very dissatisfied" <?= isChecked('q8_security_satisfaction', 'Very dissatisfied', $submitted_data) ?>> Very dissatisfied</label>
                 </div>
                 <?php if(isset($errors['q8_security_satisfaction'])): ?><div class="error-message"><?= $errors['q8_security_satisfaction'] ?></div><?php endif; ?>
             </div>

             <div class="question-group">
                 <label class="question-text" for="q9_navigation_problems">9. Did you encounter any problems in navigating through the exam?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                     <label><input type="radio" name="q9_navigation_problems" value="No problems" <?= isChecked('q9_navigation_problems', 'No problems', $submitted_data) ?> required> No problems</label>
                     <label><input type="radio" name="q9_navigation_problems" value="Minor problems" <?= isChecked('q9_navigation_problems', 'Minor problems', $submitted_data) ?>> Minor problems</label>
                     <label><input type="radio" name="q9_navigation_problems" value="Major problems" <?= isChecked('q9_navigation_problems', 'Major problems', $submitted_data) ?>> Major problems</label>
                 </div>
                 <?php if(isset($errors['q9_navigation_problems'])): ?><div class="error-message"><?= $errors['q9_navigation_problems'] ?></div><?php endif; ?>
             </div>

            <div class="question-group">
                <label class="question-text" for="q10_system_rating">10. Overall, how would you rate the electronic exam system?<span class="required-asterisk">*</span></label>
                <div class="options-group">
                    <label><input type="radio" name="q10_system_rating" value="Excellent" <?= isChecked('q10_system_rating', 'Excellent', $submitted_data) ?> required> Excellent</label>
                    <label><input type="radio" name="q10_system_rating" value="Good" <?= isChecked('q10_system_rating', 'Good', $submitted_data) ?>> Good</label>
                    <label><input type="radio" name="q10_system_rating" value="Fair" <?= isChecked('q10_system_rating', 'Fair', $submitted_data) ?>> Fair</label>
                    <label><input type="radio" name="q10_system_rating" value="Poor" <?= isChecked('q10_system_rating', 'Poor', $submitted_data) ?>> Poor</label>
                </div>
                 <?php if(isset($errors['q10_system_rating'])): ?><div class="error-message"><?= $errors['q10_system_rating'] ?></div><?php endif; ?>
            </div>


             <h2>Non-System / Environment-Related Questions</h2>

             <div class="question-group">
                 <label class="question-text" for="q11_room_conducive">11. Was the exam room comfortable and conducive for taking the test?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                     <label><input type="radio" name="q11_room_conducive" value="Yes" <?= isChecked('q11_room_conducive', 'Yes', $submitted_data) ?> required> Yes</label>
                     <label><input type="radio" name="q11_room_conducive" value="No" <?= isChecked('q11_room_conducive', 'No', $submitted_data) ?>> No</label>
                     <label><input type="radio" name="q11_room_conducive" value="Somewhat" <?= isChecked('q11_room_conducive', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                 </div>
                  <?php if(isset($errors['q11_room_conducive'])): ?><div class="error-message"><?= $errors['q11_room_conducive'] ?></div><?php endif; ?>
             </div>

            <div class="question-group">
                <label class="question-text" for="q12_equipment_comfortable">12. Were the computers and chairs provided comfortable and in good condition?<span class="required-asterisk">*</span></label>
                <div class="options-group">
                     <label><input type="radio" name="q12_equipment_comfortable" value="Yes" <?= isChecked('q12_equipment_comfortable', 'Yes', $submitted_data) ?> required> Yes</label>
                     <label><input type="radio" name="q12_equipment_comfortable" value="No" <?= isChecked('q12_equipment_comfortable', 'No', $submitted_data) ?>> No</label>
                     <label><input type="radio" name="q12_equipment_comfortable" value="Somewhat" <?= isChecked('q12_equipment_comfortable', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                 </div>
                  <?php if(isset($errors['q12_equipment_comfortable'])): ?><div class="error-message"><?= $errors['q12_equipment_comfortable'] ?></div><?php endif; ?>
            </div>

            <div class="question-group">
                 <label class="question-text" for="q13_enough_time">13. Was there enough time provided for you to complete the test without feeling rushed?<span class="required-asterisk">*</span></label>
                <div class="options-group">
                     <label><input type="radio" name="q13_enough_time" value="Yes" <?= isChecked('q13_enough_time', 'Yes', $submitted_data) ?> required> Yes</label>
                     <label><input type="radio" name="q13_enough_time" value="No" <?= isChecked('q13_enough_time', 'No', $submitted_data) ?>> No</label>
                     <label><input type="radio" name="q13_enough_time" value="Somewhat" <?= isChecked('q13_enough_time', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                 </div>
                  <?php if(isset($errors['q13_enough_time'])): ?><div class="error-message"><?= $errors['q13_enough_time'] ?></div><?php endif; ?>
             </div>

            <div class="question-group">
                <label class="question-text" for="q14_facilitator_instructions">14. Did you find the instructions from the exam facilitators clear and helpful before the test started?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                     <label><input type="radio" name="q14_facilitator_instructions" value="Yes" <?= isChecked('q14_facilitator_instructions', 'Yes', $submitted_data) ?> required> Yes</label>
                     <label><input type="radio" name="q14_facilitator_instructions" value="No" <?= isChecked('q14_facilitator_instructions', 'No', $submitted_data) ?>> No</label>
                     <label><input type="radio" name="q14_facilitator_instructions" value="Somewhat" <?= isChecked('q14_facilitator_instructions', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                 </div>
                  <?php if(isset($errors['q14_facilitator_instructions'])): ?><div class="error-message"><?= $errors['q14_facilitator_instructions'] ?></div><?php endif; ?>
             </div>

            <div class="question-group">
                <label class="question-text" for="q15_facilitator_helpful">15. Were the exam facilitators/proctors helpful and approachable?<span class="required-asterisk">*</span></label>
                <div class="options-group">
                      <label><input type="radio" name="q15_facilitator_helpful" value="Yes" <?= isChecked('q15_facilitator_helpful', 'Yes', $submitted_data) ?> required> Yes</label>
                      <label><input type="radio" name="q15_facilitator_helpful" value="No" <?= isChecked('q15_facilitator_helpful', 'No', $submitted_data) ?>> No</label>
                      <label><input type="radio" name="q15_facilitator_helpful" value="Somewhat" <?= isChecked('q15_facilitator_helpful', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                  </div>
                   <?php if(isset($errors['q15_facilitator_helpful'])): ?><div class="error-message"><?= $errors['q15_facilitator_helpful'] ?></div><?php endif; ?>
             </div>

            <div class="question-group">
                 <label class="question-text" for="q16_environment_quiet">16. Did you feel the testing environment was quiet and free from distractions?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                       <label><input type="radio" name="q16_environment_quiet" value="Yes" <?= isChecked('q16_environment_quiet', 'Yes', $submitted_data) ?> required> Yes</label>
                       <label><input type="radio" name="q16_environment_quiet" value="No" <?= isChecked('q16_environment_quiet', 'No', $submitted_data) ?>> No</label>
                       <label><input type="radio" name="q16_environment_quiet" value="Somewhat" <?= isChecked('q16_environment_quiet', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                   </div>
                    <?php if(isset($errors['q16_environment_quiet'])): ?><div class="error-message"><?= $errors['q16_environment_quiet'] ?></div><?php endif; ?>
             </div>

             <div class="question-group">
                 <label class="question-text" for="q17_issues_addressed">17. If you encountered any issues during the exam, were they addressed promptly by the proctors?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                      <label><input type="radio" name="q17_issues_addressed" value="Yes" <?= isChecked('q17_issues_addressed', 'Yes', $submitted_data) ?> required> Yes</label>
                      <label><input type="radio" name="q17_issues_addressed" value="No" <?= isChecked('q17_issues_addressed', 'No', $submitted_data) ?>> No</label>
                      <label><input type="radio" name="q17_issues_addressed" value="Somewhat" <?= isChecked('q17_issues_addressed', 'Somewhat', $submitted_data) ?>> Somewhat</label>
                      <label><input type="radio" name="q17_issues_addressed" value="Did not experience any issues" <?= isChecked('q17_issues_addressed', 'Did not experience any issues', $submitted_data) ?>> Did not experience any issues</label>
                  </div>
                   <?php if(isset($errors['q17_issues_addressed'])): ?><div class="error-message"><?= $errors['q17_issues_addressed'] ?></div><?php endif; ?>
             </div>

             <div class="question-group">
                 <label class="question-text" for="q18_prefer_computer_based">18. Do you prefer taking this type of exam (computer-based) over traditional pen-and-paper tests?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                      <label><input type="radio" name="q18_prefer_computer_based" value="Yes" <?= isChecked('q18_prefer_computer_based', 'Yes', $submitted_data) ?> required> Yes</label>
                      <label><input type="radio" name="q18_prefer_computer_based" value="No" <?= isChecked('q18_prefer_computer_based', 'No', $submitted_data) ?>> No</label>
                      <label><input type="radio" name="q18_prefer_computer_based" value="Not Sure" <?= isChecked('q18_prefer_computer_based', 'Not Sure', $submitted_data) ?>> Not Sure</label>
                  </div>
                   <?php if(isset($errors['q18_prefer_computer_based'])): ?><div class="error-message"><?= $errors['q18_prefer_computer_based'] ?></div><?php endif; ?>
             </div>

            <div class="question-group">
                 <label class="question-text" for="q19_distractions">19. Were there any distractions or noises that affected your concentration during the test?<span class="required-asterisk">*</span></label>
                 <div class="options-group">
                       <label><input type="radio" name="q19_distractions" value="No distractions" <?= isChecked('q19_distractions', 'No distractions', $submitted_data) ?> required> No distractions</label>
                       <label><input type="radio" name="q19_distractions" value="Minor distractions" <?= isChecked('q19_distractions', 'Minor distractions', $submitted_data) ?>> Minor distractions</label>
                       <label><input type="radio" name="q19_distractions" value="Major distractions" <?= isChecked('q19_distractions', 'Major distractions', $submitted_data) ?>> Major distractions</label>
                   </div>
                    <?php if(isset($errors['q19_distractions'])): ?><div class="error-message"><?= $errors['q19_distractions'] ?></div><?php endif; ?>
             </div>

             <div class="question-group">
                 <label class="question-text" for="q20_suggestions">20. What improvements would you suggest to enhance your experience with the computerized test?</label>
                 <textarea name="q20_suggestions" id="q20_suggestions" class="form-textarea"><?= e($submitted_data['q20_suggestions'] ?? '') ?></textarea>
                 <!-- No error message for optional field -->
             </div>


            <button type="submit" class="submit-btn">Submit Evaluation</button>

        </form>

    </div><!-- /container -->
    <?php require_once __DIR__ . '/../views/partials/foot.php'; // Adjust path ?>
</body>
</html>