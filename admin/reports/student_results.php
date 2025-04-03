<?php
// Student exam results page for admins (admin/reports/student_results.php)
require_once __DIR__ . '/../../bootstrap.php';

// Require admin login
requireAdminLogin();

// Get all students with completed exams
$studentsWithExamsStatement = $db->query(
    "SELECT s.student_id, s.passcode, s.first_name, s.last_name, 
            COUNT(ta.attempt_id) as attempt_count,
            MAX(ta.created_at) as last_attempt_date
     FROM students s
     JOIN test_attempts ta ON s.student_id = ta.student_id
     WHERE ta.status = 'Completed' OR ta.status = 'Expired'
     GROUP BY s.student_id
     ORDER BY s.last_name, s.first_name"
);
$studentsWithExams = $studentsWithExamsStatement->fetchAll();

// Get details for a specific attempt if student_id is provided
$selectedStudent = null;
$selectedAttempt = null;
$subjectScores = [];
$subjects = [];

if (isset($_GET['student_id']) && !empty($_GET['student_id'])) {
    $studentId = (int)$_GET['student_id'];
    
    // Get student details
    $studentStatement = $db->query(
        "SELECT s.*, 
                sc.school_name, 
                str.name AS strand_name, 
                c1.course_name AS first_preference, 
                c2.course_name AS second_preference
         FROM students s
         LEFT JOIN schools sc ON s.school_id = sc.id
         LEFT JOIN strands str ON s.strand_id = str.strand_id
         LEFT JOIN courses c1 ON s.first_preference_id = c1.course_id
         LEFT JOIN courses c2 ON s.second_preference_id = c2.course_id
         WHERE s.student_id = ?",
        [$studentId]
    );
    $selectedStudent = $studentStatement->fetch();
    
    // Get all attempts for this student
    $attemptsStatement = $db->query(
        "SELECT attempt_id, start_time, end_time, status, total_score, created_at
         FROM test_attempts
         WHERE student_id = ? AND (status = 'Completed' OR status = 'Expired')
         ORDER BY created_at DESC",
        [$studentId]
    );
    $attempts = $attemptsStatement->fetchAll();
    
    // Get the selected attempt (default to latest)
    $attemptId = isset($_GET['attempt_id']) && !empty($_GET['attempt_id']) 
        ? (int)$_GET['attempt_id'] 
        : ($attempts[0]['attempt_id'] ?? null);
    
    if ($attemptId) {
        // Get the selected attempt details
        foreach ($attempts as $attempt) {
            if ($attempt['attempt_id'] == $attemptId) {
                $selectedAttempt = $attempt;
                break;
            }
        }
        
        // Get all subjects
        $subjectsStatement = $db->query("SELECT id, name FROM subjects ORDER BY name");
        $subjects = $subjectsStatement->fetchAll();
        
        // Get scores for each subject for this attempt
        $subjectScoresStatement = $db->query(
            "SELECT sb.subject_id, s.name AS subject_name, sb.score, sb.items_attempted, sb.items_correct
             FROM attempt_scores_by_subject sb
             JOIN subjects s ON sb.subject_id = s.id
             WHERE sb.attempt_id = ?
             ORDER BY s.name",
            [$attemptId]
        );
        $subjectScores = $subjectScoresStatement->fetchAll();
        
        // Map scores by subject_id for easy access
        $scoresBySubject = [];
        foreach ($subjectScores as $score) {
            $scoresBySubject[$score['subject_id']] = $score;
        }
    }
}

$title = "Student Exam Results - Admin Dashboard";
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
            background-color: #2196F3;
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
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-role {
            margin-right: 20px;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
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
        
        .back-link {
            margin-bottom: 20px;
        }
        
        .back-link a {
            color: #2196F3;
            text-decoration: none;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .panel {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .panel h2 {
            color: #333;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        
        table tr:hover {
            background-color: #f9f9f9;
        }
        
        .button {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        
        .button:hover {
            background-color: #0b7dda;
        }
        
        .student-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .student-info p {
            margin: 8px 0;
        }
        
        .student-info .label {
            font-weight: bold;
            color: #666;
        }
        
        .attempt-select {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        .score-summary {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
        }
        
        .total-score {
            font-size: 24px;
            font-weight: bold;
            color: #2e7d32;
        }
        
        .total-score .label {
            font-size: 16px;
            color: #555;
        }
        
        .score-percentage {
            font-size: 20px;
            color: #2e7d32;
        }
        
        .data-filters {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .search-box {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 250px;
        }
        
        /* Score indicators */
        .score-indicator {
            width: 100%;
            background-color: #f1f1f1;
            border-radius: 4px;
            margin-top: 5px;
        }
        
        .score-fill {
            height: 10px;
            background-color: #4CAF50;
            border-radius: 4px;
        }
        
        /* For scores below different thresholds */
        .score-fill.low {
            background-color: #f44336;
        }
        
        .score-fill.medium {
            background-color: #ff9800;
        }
        
        .score-fill.high {
            background-color: #4CAF50;
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
        <h1 class="welcome">Student Exam Results</h1>
        <div class="user-info">
            <div class="user-role"><?= htmlspecialchars($_SESSION['admin_role']) ?></div>
            <div class="logout">
                <a href="/admin/logout.php">Logout</a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php flashMessage(); ?>
        
        <div class="back-link">
            <a href="/admin/dashboard.php">&larr; Back to Dashboard</a>
        </div>
        
        <?php if ($selectedStudent): ?>
            <!-- Student Detail View -->
            <div class="panel">
                <h2>Student Information</h2>
                <div class="student-info">
                    <div>
                        <p><span class="label">Name:</span> <?= htmlspecialchars($selectedStudent['first_name'] . ' ' . $selectedStudent['last_name']) ?></p>
                        <p><span class="label">Passcode:</span> <?= htmlspecialchars($selectedStudent['passcode']) ?></p>
                        <p><span class="label">School:</span> <?= htmlspecialchars($selectedStudent['school_name'] ?? 'Not set') ?></p>
                        <p><span class="label">Strand:</span> <?= htmlspecialchars($selectedStudent['strand_name'] ?? 'Not set') ?></p>
                    </div>
                    <div>
                        <p><span class="label">Email:</span> <?= htmlspecialchars($selectedStudent['email'] ?? 'Not set') ?></p>
                        <p><span class="label">GWA:</span> <?= htmlspecialchars($selectedStudent['gwa'] ?? 'Not set') ?></p>
                        <p><span class="label">First Choice:</span> <?= htmlspecialchars($selectedStudent['first_preference'] ?? 'Not set') ?></p>
                        <p><span class="label">Second Choice:</span> <?= htmlspecialchars($selectedStudent['second_preference'] ?? 'Not set') ?></p>
                    </div>
                </div>
                
                <?php if (isset($attempts) && !empty($attempts)): ?>
                    <div class="attempt-select">
                        <form action="" method="get">
                            <input type="hidden" name="student_id" value="<?= $selectedStudent['student_id'] ?>">
                            <label for="attempt_id">Select Attempt:</label>
                            <select name="attempt_id" id="attempt_id" onchange="this.form.submit()">
                                <?php foreach ($attempts as $attempt): ?>
                                    <option value="<?= $attempt['attempt_id'] ?>" 
                                            <?= ($selectedAttempt && $attempt['attempt_id'] == $selectedAttempt['attempt_id']) ? 'selected' : '' ?>>
                                        <?= date('F d, Y h:i A', strtotime($attempt['created_at'])) ?>
                                        (<?= $attempt['status'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                <?php endif; ?>
                
                <?php if ($selectedAttempt): ?>
                    <h3>Exam Details</h3>
                    <p>
                        <span class="label">Date:</span> <?= date('F d, Y', strtotime($selectedAttempt['created_at'])) ?> |
                        <span class="label">Start Time:</span> <?= date('h:i A', strtotime($selectedAttempt['start_time'])) ?> |
                        <span class="label">End Time:</span> <?= date('h:i A', strtotime($selectedAttempt['end_time'])) ?> |
                        <span class="label">Status:</span> <?= $selectedAttempt['status'] ?>
                    </p>
                    
                    <?php if (!empty($subjectScores)): ?>
                        <h3>Subject Scores</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Score</th>
                                    <th>Items Attempted</th>
                                    <th>Items Correct</th>
                                    <th>Percentage</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subjectScores as $score): ?>
                                    <?php 
                                        $percentage = $score['items_attempted'] > 0 
                                            ? ($score['items_correct'] / $score['items_attempted']) * 100 
                                            : 0;
                                        
                                        // Determine color class based on percentage
                                        $colorClass = 'low';
                                        if ($percentage >= 70) {
                                            $colorClass = 'high';
                                        } elseif ($percentage >= 50) {
                                            $colorClass = 'medium';
                                        }
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($score['subject_name']) ?></td>
                                        <td><?= $score['score'] ?></td>
                                        <td><?= $score['items_attempted'] ?></td>
                                        <td><?= $score['items_correct'] ?></td>
                                        <td><?= number_format($percentage, 2) ?>%</td>
                                        <td>
                                            <div class="score-indicator">
                                                <div class="score-fill <?= $colorClass ?>" style="width: <?= $percentage ?>%;"></div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <div class="score-summary">
                            <div class="total-score">
                                <span class="label">Total Score:</span> 
                                <?= $selectedAttempt['total_score'] ?> / <?= array_sum(array_column($subjectScores, 'items_attempted')) ?>
                            </div>
                            <?php 
                                $totalItems = array_sum(array_column($subjectScores, 'items_attempted'));
                                $overallPercentage = $totalItems > 0 
                                    ? ($selectedAttempt['total_score'] / $totalItems) * 100 
                                    : 0;
                            ?>
                            <div class="score-percentage">
                                <?= number_format($overallPercentage, 2) ?>%
                            </div>
                        </div>
                    <?php else: ?>
                        <p>No subject scores available for this attempt.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>No exam attempts available for this student.</p>
                <?php endif; ?>
                
                <div class="back-link" style="margin-top: 20px;">
                    <a href="/admin/reports/student_results.php">&larr; Back to Students List</a>
                </div>
            </div>
        <?php else: ?>
            <!-- Students List View -->
            <div class="panel">
                <h2>Students with Completed Exams</h2>
                
                <div class="data-filters">
                    <input type="text" class="search-box" id="studentSearch" placeholder="Search by name or passcode..." onkeyup="filterStudents()">
                </div>
                
                <?php if (empty($studentsWithExams)): ?>
                    <p>No students have completed any exams yet.</p>
                <?php else: ?>
                    <table id="studentsTable">
                        <thead>
                            <tr>
                                <th>Passcode</th>
                                <th>Name</th>
                                <th>Attempts</th>
                                <th>Last Attempt</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($studentsWithExams as $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['passcode']) ?></td>
                                    <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                    <td><?= $student['attempt_count'] ?></td>
                                    <td><?= date('F d, Y h:i A', strtotime($student['last_attempt_date'])) ?></td>
                                    <td>
                                        <a href="/admin/reports/student_results.php?student_id=<?= $student['student_id'] ?>" class="button">View Results</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function filterStudents() {
            var input, filter, table, tr, td1, td2, i, txtValue1, txtValue2;
            input = document.getElementById("studentSearch");
            filter = input.value.toUpperCase();
            table = document.getElementById("studentsTable");
            tr = table.getElementsByTagName("tr");
            
            for (i = 1; i < tr.length; i++) { // Start from 1 to skip header row
                td1 = tr[i].getElementsByTagName("td")[0]; // Passcode column
                td2 = tr[i].getElementsByTagName("td")[1]; // Name column
                
                if (td1 && td2) {
                    txtValue1 = td1.textContent || td1.innerText;
                    txtValue2 = td2.textContent || td2.innerText;
                    
                    if (txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>
</html>