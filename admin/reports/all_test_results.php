<?php
// All students test results page (admin/reports/all_test_results.php)
require_once __DIR__ . '/../../bootstrap.php';

// Require admin login
requireAdminLogin();

// Get all students with completed exams
$studentsWithExamsStatement = $db->query(
    "SELECT
        s.student_id,
        s.passcode,
        s.first_name,
        s.last_name,
        ta.attempt_id,
        ta.total_score,
        ta.created_at as test_date,
        ta.status
     FROM students s
     JOIN test_attempts ta ON s.student_id = ta.student_id
     WHERE ta.status = 'Completed' OR ta.status = 'Expired'
     ORDER BY ta.created_at DESC"
);
$studentsWithExams = $studentsWithExamsStatement->fetchAll();

// Get all subjects - still needed to map IDs later
$subjectsStatement = $db->query("SELECT id, name FROM subjects ORDER BY name");
$allSubjects = $subjectsStatement->fetchAll();

// Define the desired order of subjects for display
$subjectDisplayOrder = [
    'English',
    'Science',
    'Mathematics',
    'Social Science',
    'Filipino',
    'Abstract Reasoning'
];

// Create a map of subject name to ID for easier lookup later
$subjectNameToIdMap = [];
foreach ($allSubjects as $subject) {
    $subjectNameToIdMap[$subject['name']] = $subject['id'];
}

// Create an array with subject scores for each student
$studentScores = [];

if (!empty($studentsWithExams)) {
    // For each student's attempt, get their subject scores
    foreach ($studentsWithExams as $student) {
        $attemptId = $student['attempt_id'];

        // Get scores for each subject for this attempt
        $subjectScoresStatement = $db->query(
            "SELECT
                sb.subject_id,
                s.name AS subject_name,
                sb.score
             FROM attempt_scores_by_subject sb
             JOIN subjects s ON sb.subject_id = s.id
             WHERE sb.attempt_id = ?",
            [$attemptId]
        );
        $attemptSubjectScores = $subjectScoresStatement->fetchAll();

        // Create a map of subject_id => score for this student's attempt
        $scoresBySubjectId = [];
        foreach ($attemptSubjectScores as $score) {
            $scoresBySubjectId[$score['subject_id']] = $score['score'];
        }

        // Store the student data with their scores mapped by subject ID
        $studentScores[] = [
            'student_id' => $student['student_id'],
            'passcode' => $student['passcode'],
            'name' => $student['first_name'] . ' ' . $student['last_name'],
            'attempt_id' => $attemptId,
            'test_date' => $student['test_date'],
            'status' => $student['status'],
            'total_score' => $student['total_score'],
            'subject_scores' => $scoresBySubjectId // Store scores mapped by ID
        ];
    }
}

$title = "All Student Test Results - Admin Dashboard";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1400px;
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
            font-size: 14px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 10;
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

        .data-table-container {
            overflow-x: auto;
            max-width: 100%;
        }

        .search-box {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 15px;
        }

        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .controls-left {
            display: flex;
            gap: 10px;
        }

        .export-buttons {
            display: flex;
            gap: 10px;
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

        .score-cell {
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1 class="welcome">All Student Test Results</h1>
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
            <a href="/admin/dashboard.php">← Back to Dashboard</a>
        </div>

        <div class="panel">
            <h2>All Student Test Results</h2>

            <div class="controls">
                <div class="controls-left">
                    <input type="text" class="search-box" id="studentSearch" placeholder="Search by name or passcode..." onkeyup="filterStudents()">
                </div>
                <div class="export-buttons">
                    <button onclick="exportTableToCSV('test_results.csv')" class="button">Export to CSV</button>
                </div>
            </div>

            <?php if (empty($studentScores)): ?>
                <p>No students have completed any exams yet.</p>
            <?php else: ?>
                <div class="data-table-container">
                    <table id="studentsTable">
                        <thead>
                            <tr>
                                <th>Passcode</th>
                                <th>Name</th>
                                <?php // Use the defined order for headers ?>
                                <?php foreach ($subjectDisplayOrder as $subjectName): ?>
                                    <th><?= htmlspecialchars($subjectName) ?></th>
                                <?php endforeach; ?>
                                <th>Total Score</th>
                                <th>Test Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($studentScores as $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['passcode']) ?></td>
                                    <td><?= htmlspecialchars($student['name']) ?></td>

                                    <?php // Iterate through the defined subject order to display scores ?>
                                    <?php foreach ($subjectDisplayOrder as $subjectName):
                                        // Find the subject ID using the map
                                        $subjectId = $subjectNameToIdMap[$subjectName] ?? null;
                                        $score = '-'; // Default value if subject not found or no score
                                        // Check if the subject ID exists and if the student has a score for it
                                        if ($subjectId !== null && isset($student['subject_scores'][$subjectId])) {
                                            $score = $student['subject_scores'][$subjectId];
                                        }
                                    ?>
                                        <td class="score-cell"><?= htmlspecialchars($score) ?></td>
                                    <?php endforeach; ?>

                                    <td class="score-cell"><?= htmlspecialchars($student['total_score']) ?></td>
                                    <td><?= date('Y-m-d H:i', strtotime($student['test_date'])) ?></td>
                                    <td>
                                        <a href="/admin/reports/student_results.php?student_id=<?= $student['student_id'] ?>&attempt_id=<?= $student['attempt_id'] ?>" class="button">View Details</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Function to filter the table based on search input
        function filterStudents() {
            var input, filter, table, tr, td1, td2, i, txtValue1, txtValue2;
            input = document.getElementById("studentSearch");
            filter = input.value.toUpperCase();
            table = document.getElementById("studentsTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows (start from 1 to skip header)
            for (i = 1; i < tr.length; i++) {
                td1 = tr[i].getElementsByTagName("td")[0]; // Passcode column
                td2 = tr[i].getElementsByTagName("td")[1]; // Name column

                if (td1 && td2) {
                    txtValue1 = td1.textContent || td1.innerText;
                    txtValue2 = td2.textContent || td2.innerText;

                    // Check if passcode or name matches the filter
                    if (txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = ""; // Show row
                    } else {
                        tr[i].style.display = "none"; // Hide row
                    }
                }
            }
        }

        // Function to export the table to CSV
        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll("#studentsTable tr");

            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll("td, th");

                // Exclude the last column (Actions)
                for (var j = 0; j < cols.length - 1; j++) {
                    // Clean up cell text: remove line breaks and trim whitespace
                    let cellText = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                    // Escape double quotes by doubling them and enclose in double quotes
                    row.push('"' + cellText.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(","));
            }

            // Download CSV file
            downloadCSV(csv.join("\n"), filename);
        }

        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            // BOM for UTF-8 handling in Excel
            const BOM = "\uFEFF";

            // Create CSV file blob
            csvFile = new Blob([BOM + csv], {type: "text/csv;charset=utf-8;"});

            // Create download link element
            downloadLink = document.createElement("a");

            // Set file name
            downloadLink.download = filename;

            // Create a link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);

            // Hide download link
            downloadLink.style.display = "none";

            // Add the link to the DOM
            document.body.appendChild(downloadLink);

            // Trigger the download
            downloadLink.click();

            // Remove the link from the DOM
            document.body.removeChild(downloadLink);
        }
    </script>
</body>
</html>