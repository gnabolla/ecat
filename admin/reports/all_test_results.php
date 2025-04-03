<?php
// All students test results page (admin/reports/all_test_results.php)

// --- Ensure this path is correct ---
require_once __DIR__ . '/../../bootstrap.php';

// Require admin login
requireAdminLogin();

// --- Database Connection (assuming $db is initialized in bootstrap.php or here) ---
// If $db isn't globally available from bootstrap.php, uncomment the next lines:
/*
if (!isset($db)) {
    try {
        $db = new Database($config['database']);
    } catch (PDOException $e) {
        error_log("Database Connection Error: " . $e->getMessage());
        die("Error connecting to the database.");
    }
}
*/

// --- Fetch Data (Your existing logic) ---
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
     WHERE ta.status = 'Completed' OR ta.status = 'Expired'  /* Consider if you only want 'Completed' */
     ORDER BY s.last_name ASC, s.first_name ASC, ta.created_at DESC" /* Added name sorting, keep latest attempt per student if multiple */
);
// Use fetchAll(PDO::FETCH_ASSOC) for associative arrays if not default
$studentsWithExams = $studentsWithExamsStatement->fetchAll(PDO::FETCH_ASSOC);

// Get all subjects - still needed to map IDs later
$subjectsStatement = $db->query("SELECT id, name FROM subjects ORDER BY name");
$allSubjects = $subjectsStatement->fetchAll(PDO::FETCH_ASSOC);

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
    $subjectNameToIdMap[trim(strtoupper($subject['name']))] = $subject['id']; // Trim and uppercase for robust matching
}

// Create an array with subject scores for each student's *latest* completed/expired attempt
$studentScores = [];
$processedStudents = []; // Keep track of students already added

if (!empty($studentsWithExams)) {
    foreach ($studentsWithExams as $studentAttempt) {
        $studentId = $studentAttempt['student_id'];

        // If we haven't processed this student yet (handling multiple attempts if query returns them)
        if (!isset($processedStudents[$studentId])) {
            $attemptId = $studentAttempt['attempt_id'];

            // Get scores for each subject for this specific attempt
            $subjectScoresStatement = $db->query(
                "SELECT
                    sb.subject_id,
                    s.name AS subject_name, /* Subject name might be useful for debugging */
                    sb.score
                 FROM attempt_scores_by_subject sb
                 JOIN subjects s ON sb.subject_id = s.id
                 WHERE sb.attempt_id = ?",
                [$attemptId]
            );
            // Use fetchAll(PDO::FETCH_ASSOC)
            $attemptSubjectScores = $subjectScoresStatement->fetchAll(PDO::FETCH_ASSOC);

            // Create a map of subject_id => score for this student's attempt
            $scoresBySubjectId = [];
            foreach ($attemptSubjectScores as $score) {
                $scoresBySubjectId[$score['subject_id']] = $score['score'];
            }

            // Store the student data with their scores mapped by subject ID
            $studentScores[] = [
                'student_id' => $studentId,
                'passcode' => $studentAttempt['passcode'],
                'name' => $studentAttempt['first_name'] . ' ' . $studentAttempt['last_name'],
                'attempt_id' => $attemptId, // Keep the attempt ID associated with these scores
                'test_date' => $studentAttempt['test_date'],
                'status' => $studentAttempt['status'],
                'total_score' => $studentAttempt['total_score'],
                'subject_scores' => $scoresBySubjectId // Store scores mapped by ID
            ];
            $processedStudents[$studentId] = true; // Mark student as processed
        }
    }
}


$title = "All Student Test Results - Admin Dashboard";

// --- Helper functions (ensure these are loaded, e.g., via bootstrap.php) ---
if (!function_exists('flashMessage')) {
    // Define a basic flash message function if not available
    function flashMessage() {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            $type = isset($message['type']) && $message['type'] === 'error' ? 'error' : 'success'; // Basic type check
            echo '<div class="flash-message ' . htmlspecialchars($type) . '">' . htmlspecialchars($message['message']) . '</div>';
            unset($_SESSION['flash_message']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        /* YOUR EXISTING CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1400px; /* Adjusted for potentially more columns */
            margin: 0 auto;
            padding: 20px;
        }

        header {
            background-color: #2196F3; /* Material Blue */
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .welcome {
            margin: 0;
            font-size: 1.5em;
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
            transition: background-color 0.2s ease;
        }

        .logout a:hover {
            background-color: rgba(0, 0, 0, 0.4);
        }

        .back-link {
            margin-bottom: 20px;
        }

        .back-link a {
            color: #2196F3;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .panel {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
        }

        .panel h2 {
            color: #333;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
            font-size: 1.4em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        table th, table td {
            padding: 12px 10px; /* Slightly more padding */
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: middle; /* Align vertically */
        }

        table th {
            background-color: #f8f9fa; /* Lighter gray */
            font-weight: bold;
            position: sticky; /* Keep header sticky */
            top: 0;
            z-index: 10; /* Ensure header is above content */
            white-space: nowrap; /* Prevent header text wrapping */
        }

        table tbody tr:hover {
            background-color: #f1f1f1; /* Slightly darker hover */
        }

        .button {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 6px 10px; /* Slightly smaller buttons */
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 13px; /* Smaller font size */
            text-align: center;
            vertical-align: middle;
            transition: background-color 0.2s ease;
            margin-right: 5px; /* Add space between buttons */
        }
        .button:last-child {
             margin-right: 0;
        }

        .button:hover {
            background-color: #0b7dda; /* Darker blue on hover */
        }
        .button-print { /* Specific style for print button if needed */
             background-color: #17a2b8; /* Teal color */
        }
        .button-print:hover {
             background-color: #117a8b;
        }


        .data-table-container {
            overflow-x: auto; /* Enable horizontal scrolling */
            max-width: 100%;
            margin-top: 15px; /* Space above table */
        }

        .search-box {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 15px;
            box-sizing: border-box; /* Include padding in width */
        }

        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
            gap: 15px; /* Space between items */
            margin-bottom: 20px;
        }

        .controls-left {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .export-buttons {
            display: flex;
            gap: 10px;
        }

        .flash-message {
            padding: 15px; /* More padding */
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            font-size: 15px;
        }

        .flash-message.error {
            background-color: #f8d7da; /* Bootstrap danger background */
            color: #721c24; /* Bootstrap danger text */
            border-color: #f5c6cb; /* Bootstrap danger border */
        }

        .flash-message.success {
            background-color: #d4edda; /* Bootstrap success background */
            color: #155724; /* Bootstrap success text */
            border-color: #c3e6cb; /* Bootstrap success border */
        }

        .score-cell {
            text-align: center !important; /* Center align scores */
            font-weight: 500; /* Slightly bolder scores */
            white-space: nowrap; /* Prevent scores wrapping */
        }
        .actions-cell {
             white-space: nowrap; /* Prevent buttons wrapping */
             text-align: center; /* Center buttons */
        }
    </style>
</head>
<body>
    <header>
        <h1 class="welcome">Admin Dashboard</h1>
        <div class="user-info">
            <?php if (isset($_SESSION['admin_role'])): ?>
                <div class="user-role">Role: <?= htmlspecialchars($_SESSION['admin_role']) ?></div>
            <?php endif; ?>
            <div class="logout">
                <a href="/admin/logout.php">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <?php flashMessage(); // Display flash messages here ?>

        <div class="back-link">
            <a href="/admin/dashboard.php">← Back to Dashboard</a>
        </div>

        <div class="panel">
            <h2>All Student Test Results</h2>

            <div class="controls">
                <div class="controls-left">
                    <input type="text" class="search-box" id="studentSearch" placeholder="Search by Name or Passcode..." onkeyup="filterStudents()">
                </div>
                <div class="export-buttons">
                    <button onclick="exportTableToCSV('student_test_results.csv')" class="button">Export to CSV</button>
                    <!-- Add other export buttons if needed -->
                </div>
            </div>

            <?php if (empty($studentScores)): ?>
                <p>No completed or expired test attempts found.</p>
            <?php else: ?>
                <div class="data-table-container">
                    <table id="studentsTable">
                        <thead>
                            <tr>
                                <th>Passcode</th>
                                <th>Name</th>
                                <?php // Dynamically create headers based on the desired display order
                                foreach ($subjectDisplayOrder as $subjectName): ?>
                                    <th class="score-cell"><?= htmlspecialchars($subjectName) ?></th>
                                <?php endforeach; ?>
                                <th class="score-cell">Total Score</th>
                                <th>Test Date</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($studentScores as $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['passcode']) ?></td>
                                    <td><?= htmlspecialchars($student['name']) ?></td>

                                    <?php
                                    // Loop through the defined subject order for consistent column display
                                    foreach ($subjectDisplayOrder as $subjectName):
                                        // Find the subject ID from the map (case-insensitive matching)
                                        $subjectId = $subjectNameToIdMap[trim(strtoupper($subjectName))] ?? null;
                                        $score = '-'; // Default display if no score found

                                        // Check if the subject ID exists and if the student has a score for this attempt
                                        if ($subjectId !== null && isset($student['subject_scores'][$subjectId])) {
                                            $score = $student['subject_scores'][$subjectId];
                                        }
                                    ?>
                                        <td class="score-cell"><?= htmlspecialchars($score) ?></td>
                                    <?php endforeach; ?>

                                    <td class="score-cell"><?= htmlspecialchars($student['total_score'] ?? '-') // Handle potentially null total score ?></td>
                                    <td><?= date('M d, Y H:i', strtotime($student['test_date'])) // More readable date format ?></td>
                                    <td class="actions-cell">
                                        <a href="/admin/reports/student_results.php?student_id=<?= htmlspecialchars($student['student_id']) ?>&attempt_id=<?= htmlspecialchars($student['attempt_id']) ?>" class="button" title="View Detailed Results">View Details</a>
                                        
                                        <!-- === ADDED PRINT BUTTON HERE === -->
                                        <a href="/admin/reports/print_gc_form1.php?student_id=<?= htmlspecialchars($student['student_id']) ?>" class="button button-print" target="_blank" title="Print GC Form 1 for <?= htmlspecialchars($student['name']) ?>">Print Form</a>
                                        <!-- === END OF ADDED PRINT BUTTON === -->

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
            let input, filter, table, tr, tdPasscode, tdName, i, txtValuePasscode, txtValueName;
            input = document.getElementById("studentSearch");
            filter = input.value.toUpperCase().trim(); // Trim whitespace
            table = document.getElementById("studentsTable");
            tbody = table.getElementsByTagName("tbody")[0]; // Get tbody element
            tr = tbody.getElementsByTagName("tr");

            // Loop through all table body rows
            for (i = 0; i < tr.length; i++) {
                tdPasscode = tr[i].getElementsByTagName("td")[0]; // Passcode column (index 0)
                tdName = tr[i].getElementsByTagName("td")[1]; // Name column (index 1)
                let displayRow = false; // Flag to decide if row should be shown

                if (tdPasscode) {
                    txtValuePasscode = (tdPasscode.textContent || tdPasscode.innerText).trim();
                    if (txtValuePasscode.toUpperCase().indexOf(filter) > -1) {
                        displayRow = true;
                    }
                }
                if (tdName) {
                    txtValueName = (tdName.textContent || tdName.innerText).trim();
                    if (txtValueName.toUpperCase().indexOf(filter) > -1) {
                         displayRow = true;
                    }
                }

                // Show or hide the row based on the flag
                tr[i].style.display = displayRow ? "" : "none";
            }
        }

        // Function to export the table to CSV (excluding the Actions column)
        function exportTableToCSV(filename) {
            var csv = [];
            var headerRow = document.querySelectorAll("#studentsTable thead tr")[0];
            var bodyRows = document.querySelectorAll("#studentsTable tbody tr");
            var numCols = headerRow.querySelectorAll("th").length;

            // Get headers (excluding the last one - Actions)
            var header = [];
            var ths = headerRow.querySelectorAll("th");
            for (var j = 0; j < numCols - 1; j++) { // Iterate up to the second to last column
                 let cellText = ths[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                 header.push('"' + cellText.replace(/"/g, '""') + '"');
            }
            csv.push(header.join(","));


            // Get visible body rows (respecting filter)
            bodyRows.forEach(function(row) {
                 // Check if the row is visible
                 if (row.style.display !== 'none') {
                     var rowData = [], cols = row.querySelectorAll("td");
                     for (var j = 0; j < numCols - 1; j++) { // Iterate up to the second to last column
                         let cellText = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                         rowData.push('"' + cellText.replace(/"/g, '""') + '"');
                     }
                     csv.push(rowData.join(","));
                 }
            });


            // Download CSV file
            downloadCSV(csv.join("\n"), filename);
        }

        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            // BOM to ensure UTF-8 compatibility in Excel
            const BOM = "\uFEFF";

            csvFile = new Blob([BOM + csv], {type: "text/csv;charset=utf-8;"});
            downloadLink = document.createElement("a");
            downloadLink.download = filename;
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
</body>
</html>