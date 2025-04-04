<?php
/**
 * Admin Page: All Student Test Results
 *
 * Displays a sortable and searchable table of all students who have
 * completed or expired test attempts, showing their latest attempt scores.
 *
 * File: admin/reports/all_test_results.php
 */

// --- Bootstrap ---
// Use require_once and __DIR__ for robust path handling.
// Ensure bootstrap.php sets up sessions, autoloading (if any),
// core functions (like requireAdminLogin, flashMessage), and potentially DB config.
require_once __DIR__ . '/../../bootstrap.php';

// --- Security ---
// Require admin-level login access for this page.
requireAdminLogin();

// --- Database Connection ---
// Assuming $db is initialized in bootstrap.php or accessible globally.
// If not, initialize it here.
if (!isset($db) || !$db instanceof Database) {
    // Load config if necessary, e.g., $config = require __DIR__ . '/../../config.php';
    try {
        // Ensure $config is available or provide default connection details if necessary
        $config = $config ?? require __DIR__ . '/../../config.php'; // Load config if not already loaded
        $db = new Database($config['database'] ?? []); // Pass config array or empty array
    } catch (Exception $e) {
        error_log("Database Connection Error in all_test_results.php: " . $e->getMessage());
        // Provide a user-friendly message. Avoid exposing detailed errors in production.
        // Using flash messages might be better if the session is already started.
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Database connection failed. Please contact support.'];
        // Redirect to a safe page or display a minimal error page.
        // For simplicity here, we'll echo and exit.
        echo "<p>Error connecting to the database. Please try again later or contact support.</p>";
        exit;
    }
}

// --- Sorting Logic ---
$allowedSortColumns = ['name', 'total_score', 'passcode', 'test_date']; // Columns allowed for sorting
$allowedOrderDirections = ['asc', 'desc'];                       // Directions allowed for sorting

// Default sorting parameters
$sort = 'name'; // Default column to sort by
$order = 'asc';  // Default sorting direction

// Get sorting parameters from the URL query string and validate them
if (isset($_GET['sort']) && in_array($_GET['sort'], $allowedSortColumns)) {
    $sort = $_GET['sort'];
}
if (isset($_GET['order']) && in_array($_GET['order'], $allowedOrderDirections)) {
    $order = $_GET['order'];
}

// Apply special default sorting rules if needed (e.g., score defaults to descending)
if ($sort === 'total_score' && !isset($_GET['order'])) { // If sorting by score and no order specified in URL
    $order = 'desc'; // Default to descending for scores
}
if ($sort === 'test_date' && !isset($_GET['order'])) { // If sorting by date and no order specified in URL
    $order = 'desc'; // Default to descending for dates (show latest first)
}
// --- End Sorting Logic ---


// --- Data Fetching ---
// Fetch base student and attempt data. Order initially to easily find the latest attempt per student.
try {
    $studentsWithExamsStatement = $db->query(
        "SELECT
            s.student_id,
            s.passcode,
            s.first_name,
            s.last_name,
            ta.attempt_id,
            ta.total_score,
            ta.created_at as test_date, -- Alias for clarity
            ta.status
         FROM students s
         JOIN test_attempts ta ON s.student_id = ta.student_id
         WHERE ta.status = 'Completed' OR ta.status = 'Expired' -- Include Expired attempts as well? Adjust if needed.
         ORDER BY s.student_id ASC, ta.created_at DESC" // Order to easily pick the latest attempt per student in the loop
    );
    // Use fetchAll with FETCH_ASSOC if not the default PDO mode
    $studentsWithExams = $studentsWithExamsStatement->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all subjects for mapping names and IDs
    $subjectsStatement = $db->query("SELECT id, name FROM subjects ORDER BY name");
    $allSubjects = $subjectsStatement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Database Query Error in all_test_results.php: " . $e->getMessage());
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Error fetching test results. Please try again.'];
    $studentsWithExams = []; // Ensure variables exist even on error
    $allSubjects = [];
    // Consider redirecting or showing an error message within the HTML structure later
}
// --- End Data Fetching ---


// --- Data Processing ---
// Define the desired order of subjects for table columns
$subjectDisplayOrder = [
    'English',
    'Science',
    'Mathematics', // Ensure exact match with DB subject names
    'Social Science',
    'Filipino',
    'Abstract Reasoning' // Ensure exact match with DB subject names
];

// Create a map of subject name (uppercase, trimmed) to ID for robust lookup
$subjectNameToIdMap = [];
foreach ($allSubjects as $subject) {
    $subjectNameToIdMap[trim(strtoupper($subject['name']))] = $subject['id'];
}

// Process fetched data to get one row per student (latest attempt) with their subject scores
$studentScores = [];
$processedStudents = []; // Helper array to track students already added (ensures only latest attempt is shown)

if (!empty($studentsWithExams)) {
    // Prepare statement for fetching subject scores - more efficient than querying inside the loop
    // We'll fetch scores for all relevant attempt IDs at once if possible, or prepare statement
    $attemptIds = array_unique(array_column($studentsWithExams, 'attempt_id')); // Get unique attempt IDs
    $scoresByAttempt = [];

    if (!empty($attemptIds)) {
        try {
            // Create placeholders for the IN clause: (?, ?, ...)
            $placeholders = implode(',', array_fill(0, count($attemptIds), '?'));
            $sqlScores = "SELECT
                            sb.attempt_id,
                            sb.subject_id,
                            sb.score,
                            sb.items_correct,
                            sb.items_attempted
                          FROM attempt_scores_by_subject sb
                          WHERE sb.attempt_id IN ({$placeholders})";

            $scoresStmt = $db->query($sqlScores, $attemptIds); // Execute with array of IDs
            $allAttemptScoresRaw = $scoresStmt->fetchAll(PDO::FETCH_ASSOC);

            // Reorganize scores indexed by attempt_id and then subject_id
            foreach ($allAttemptScoresRaw as $scoreInfo) {
                $scoresByAttempt[$scoreInfo['attempt_id']][$scoreInfo['subject_id']] = $scoreInfo;
            }
        } catch (PDOException $e) {
             error_log("Database Query Error fetching subject scores: " . $e->getMessage());
             $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Error fetching subject scores. Some data might be missing.'];
             // Continue processing with potentially missing scores
        }
    }


    // Loop through the initial fetch (ordered by student ID, latest attempt first)
    foreach ($studentsWithExams as $studentAttempt) {
        $studentId = $studentAttempt['student_id'];

        // If this student hasn't been added yet, process their latest attempt
        if (!isset($processedStudents[$studentId])) {
            $attemptId = $studentAttempt['attempt_id'];

            // Get the pre-fetched scores for this attempt
            $attemptSubjectScores = $scoresByAttempt[$attemptId] ?? []; // Default to empty array if no scores found

            // Add student data to the final array
            $studentScores[] = [
                'student_id' => $studentId,
                'passcode' => $studentAttempt['passcode'],
                'name' => trim($studentAttempt['first_name'] . ' ' . $studentAttempt['last_name']),
                'attempt_id' => $attemptId,
                'test_date' => $studentAttempt['test_date'],
                'status' => $studentAttempt['status'],
                'total_score' => $studentAttempt['total_score'],
                'subject_scores' => $attemptSubjectScores // Use the pre-fetched scores
            ];
            $processedStudents[$studentId] = true; // Mark student as processed
        }
    }
}
// --- End Data Processing ---


// --- Apply Sorting to the Processed `$studentScores` Array ---
if (!empty($studentScores)) {
    // Use usort to sort the array based on the selected column and order
    usort($studentScores, function($a, $b) use ($sort, $order) {
        $aVal = null;
        $bVal = null;

        // Get the values to compare based on the sort column
        switch ($sort) {
            case 'total_score':
                $aVal = $a['total_score'];
                $bVal = $b['total_score'];
                // Special handling for null scores (sort them consistently)
                if ($aVal === null && $bVal === null) return 0; // Both null, equal
                if ($aVal === null) return ($order === 'desc') ? 1 : -1; // Nulls last on DESC, first on ASC
                if ($bVal === null) return ($order === 'desc') ? -1 : 1; // Nulls last on DESC, first on ASC
                // Compare numerically if both are non-null
                $result = $aVal <=> $bVal;
                break;
            case 'passcode':
                $aVal = (string) ($a['passcode'] ?? ''); // Cast to string, handle potential null
                $bVal = (string) ($b['passcode'] ?? '');
                $result = strcasecmp($aVal, $bVal); // Case-insensitive string comparison
                break;
            case 'test_date':
                 // Convert dates to timestamps for reliable comparison, handle potential nulls
                 $aVal = strtotime($a['test_date'] ?? '1970-01-01');
                 $bVal = strtotime($b['test_date'] ?? '1970-01-01');
                 $result = $aVal <=> $bVal;
                 break;
            case 'name':
            default: // Default to sorting by name
                $aVal = $a['name'] ?? ''; // Handle potential null
                $bVal = $b['name'] ?? '';
                $result = strcasecmp($aVal, $bVal); // Case-insensitive string comparison
                break;
        }

        // Apply the sorting order (ASC or DESC)
        return ($order === 'desc') ? ($result * -1) : $result;
    });
}
// --- End Applying Sorting ---


// --- Helper Functions ---
// Ensure flashMessage function exists (it might be in bootstrap.php)
if (!function_exists('flashMessage')) {
    function flashMessage() {
        if (isset($_SESSION['flash_message']) && is_array($_SESSION['flash_message'])) {
            $messageData = $_SESSION['flash_message'];
            $type = isset($messageData['type']) && in_array($messageData['type'], ['success', 'error', 'warning', 'info']) ? $messageData['type'] : 'info';
            $message = isset($messageData['message']) ? trim($messageData['message']) : '';
            if (!empty($message)) {
                 echo '<div class="flash-message ' . htmlspecialchars($type) . '">' . htmlspecialchars($message) . '</div>';
            }
            unset($_SESSION['flash_message']); // Clear the message after displaying
        }
    }
}

// Helper function to generate sorting links for table headers
function getSortLink($column, $currentSort, $currentOrder) {
    $newOrder = 'asc'; // Default order for a newly clicked column

    // If clicking the currently sorted column, toggle the order
    if ($column === $currentSort) {
        $newOrder = ($currentOrder === 'asc') ? 'desc' : 'asc';
    }
    // Apply default sort orders for specific columns if they are not the current sort column
    elseif ($column === 'total_score') {
        $newOrder = 'desc'; // Default score sort: highest first
    }
    elseif ($column === 'test_date') {
        $newOrder = 'desc'; // Default date sort: latest first
    }
    // Add more default rules here if needed

    // Preserve existing GET parameters (like search filters) while updating sort/order
    $queryParams = $_GET; // Get current query parameters
    $queryParams['sort'] = $column;
    $queryParams['order'] = $newOrder;
    // Rebuild the query string
    return "?" . http_build_query($queryParams);
}

// Helper function to display sort indicator icon (Font Awesome)
function getSortIndicator($column, $currentSort, $currentOrder) {
    if ($column === $currentSort) {
        // Ensure Font Awesome CSS is linked in the HTML <head>
        return ($currentOrder === 'asc') ? ' <i class="fas fa-sort-up"></i>' : ' <i class="fas fa-sort-down"></i>';
    }
    return ''; // No indicator if this column is not being sorted
}
// --- End Helper Functions ---


// --- Page Setup ---
$title = "All Student Test Results - Admin Dashboard";
// You might include header partials here if using a template system
// require_once __DIR__ . '/../partials/admin_head.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <!-- Link Font Awesome for sort icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Link your main CSS file if applicable -->
    <!-- <link rel="stylesheet" href="/assets/css/admin_style.css"> -->
    <style>
        /* Inline styles provided previously - keep these or move to a CSS file */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            font-size: 14px; /* Base font size */
        }
        .container {
            max-width: 1400px; /* Wider container for more columns */
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #2196F3; /* Example Blue */
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .welcome {
            margin: 0;
            font-size: 1.5em;
            font-weight: normal;
        }
        .user-info { display: flex; align-items: center; gap: 15px; }
        .user-role {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 10px; border-radius: 4px; font-size: 0.9em;
        }
        .logout a {
            color: white; text-decoration: none; background-color: rgba(0, 0, 0, 0.2);
            padding: 8px 15px; border-radius: 4px; transition: background-color 0.2s ease;
        }
        .logout a:hover { background-color: rgba(0, 0, 0, 0.4); }
        .back-link { margin-bottom: 20px; }
        .back-link a { color: #2196F3; text-decoration: none; font-weight: bold; }
        .back-link a:hover { text-decoration: underline; }
        .panel {
            background-color: white; border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 25px; margin-bottom: 20px;
        }
        .panel h2 {
            color: #333; margin-top: 0; border-bottom: 1px solid #eee;
            padding-bottom: 15px; margin-bottom: 20px; font-size: 1.4em;
        }
        table { width: 100%; border-collapse: collapse; }
        table th, table td {
            padding: 10px 8px; /* Adjust padding */
            text-align: left; border-bottom: 1px solid #eee; vertical-align: middle;
        }
        table th {
            background-color: #f8f9fa; font-weight: bold; position: sticky;
            top: 0; /* For sticky header */ z-index: 10; white-space: nowrap;
        }
        /* Style for sortable table headers */
        table th a { color: inherit; text-decoration: none; display: inline-block; }
        table th a:hover { color: #0056b3; text-decoration: underline; }
        table th a i { margin-left: 5px; color: #6c757d; /* Icon color */}

        table tbody tr:hover { background-color: #f1f1f1; }
        table tbody td { color: #495057; } /* Default text color for cells */

        .button {
            background-color: #2196F3; color: white; border: none; padding: 6px 10px;
            border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block;
            font-size: 0.85em; text-align: center; vertical-align: middle;
            transition: background-color 0.2s ease; margin-right: 5px; margin-bottom: 3px; /* Allow wrapping */
        }
        .button:last-child { margin-right: 0; }
        .button:hover { background-color: #0b7dda; }
        .button-print { background-color: #17a2b8; }
        .button-print:hover { background-color: #117a8b; }

        .data-table-container { overflow-x: auto; max-width: 100%; margin-top: 15px; }
        .search-box {
            width: 100%; max-width: 300px; padding: 8px 10px; border: 1px solid #ddd;
            border-radius: 4px; font-size: 0.95em; box-sizing: border-box;
        }
        .controls {
            display: flex; justify-content: space-between; align-items: center;
            flex-wrap: wrap; gap: 15px; margin-bottom: 20px;
        }
        .controls-left { display: flex; gap: 10px; align-items: center; }
        .export-buttons { display: flex; gap: 10px; }
        .flash-message {
            padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid transparent;
            font-size: 0.95em;
        }
        .flash-message.error { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .flash-message.success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .flash-message.warning { background-color: #fff3cd; color: #856404; border-color: #ffeeba; }
        .flash-message.info { background-color: #d1ecf1; color: #0c5460; border-color: #bee5eb; }

        .score-cell { text-align: center !important; font-weight: 500; white-space: nowrap; }
        .actions-cell { white-space: nowrap; text-align: center; }
        .status-completed { color: #28a745; font-weight: bold; } /* Green */
        .status-expired { color: #fd7e14; font-weight: bold; }   /* Orange */
        .status-other { color: #6c757d; } /* Gray */

         /* Responsive adjustments */
        @media (max-width: 768px) {
            .container { padding: 10px; }
            header { flex-direction: column; align-items: flex-start; gap: 10px; }
            .user-info { width: 100%; justify-content: space-between;}
            .controls { flex-direction: column; align-items: stretch; }
            .search-box { max-width: none; margin-bottom: 10px;}
            .export-buttons { justify-content: flex-start; }
            table th, table td { padding: 8px 5px; font-size: 13px; }
        }
    </style>
</head>
<body>
    <header>
        <h1 class="welcome">Admin Dashboard</h1>
        <div class="user-info">
            <?php
            // Display admin role if available in session
            $roleDisplay = '';
            if (isset($_SESSION['admin_full_name'])) { // Prefer full name if available
                 $roleDisplay = htmlspecialchars($_SESSION['admin_full_name']);
            } elseif (isset($_SESSION['admin_username'])) { // Fallback to username
                 $roleDisplay = htmlspecialchars($_SESSION['admin_username']);
            }
             if (isset($_SESSION['admin_role_name'])) {
                 $roleDisplay .= ' (' . htmlspecialchars($_SESSION['admin_role_name']) . ')';
             } elseif (isset($_SESSION['admin_role'])) { // Fallback to role ID
                 $roleDisplay .= ' (Role ID: ' . htmlspecialchars($_SESSION['admin_role']) . ')';
             }
            ?>
             <?php if (!empty($roleDisplay)): ?>
                <div class="user-role" title="Logged in user and role"><?= $roleDisplay ?></div>
             <?php endif; ?>
            <div class="logout">
                <a href="/admin/logout.php" title="Logout">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <?php flashMessage(); // Display any session flash messages ?>

        <div class="back-link">
            <a href="/admin/dashboard.php">← Back to Dashboard</a>
        </div>

        <div class="panel">
            <h2>All Student Test Results</h2>

            <div class="controls">
                <div class="controls-left">
                    <input type="text" class="search-box" id="studentSearch" placeholder="Search by Name or Passcode..." onkeyup="filterStudents()" title="Filter results by student name or passcode">
                </div>
                <div class="export-buttons">
                    <button onclick="exportTableToCSV('student_test_results.csv')" class="button" title="Export current view to CSV file">Export to CSV</button>
                    <!-- Add buttons for other export formats (e.g., PDF) if needed -->
                </div>
            </div>

            <?php if (empty($studentScores)): ?>
                <p>No completed or expired test attempts found matching the criteria.</p>
            <?php else: ?>
                <div class="data-table-container">
                    <table id="studentsTable">
                        <thead>
                            <tr>
                                <!-- Sortable Passcode Header -->
                                <th>
                                    <a href="all_test_results.php<?= getSortLink('passcode', $sort, $order) ?>" title="Sort by Passcode">
                                        Passcode<?= getSortIndicator('passcode', $sort, $order) ?>
                                    </a>
                                </th>
                                <!-- Sortable Name Header -->
                                <th>
                                    <a href="all_test_results.php<?= getSortLink('name', $sort, $order) ?>" title="Sort by Name">
                                        Name<?= getSortIndicator('name', $sort, $order) ?>
                                    </a>
                                </th>
                                <?php
                                // Dynamically create non-sortable subject headers based on defined order
                                foreach ($subjectDisplayOrder as $subjectName): ?>
                                    <th class="score-cell" title="Score for <?= htmlspecialchars($subjectName) ?>"><?= htmlspecialchars($subjectName) ?></th>
                                <?php endforeach; ?>
                                <!-- Sortable Total Score Header -->
                                <th class="score-cell">
                                    <a href="all_test_results.php<?= getSortLink('total_score', $sort, $order) ?>" title="Sort by Total Score">
                                        Total Score<?= getSortIndicator('total_score', $sort, $order) ?>
                                    </a>
                                </th>
                                <!-- Sortable Test Date Header -->
                                <th>
                                     <a href="all_test_results.php<?= getSortLink('test_date', $sort, $order) ?>" title="Sort by Test Date">
                                        Test Date<?= getSortIndicator('test_date', $sort, $order) ?>
                                    </a>
                                </th>
                                <!-- Actions Header (Not sortable) -->
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($studentScores as $student):
                                // Determine status class for styling
                                $statusClass = 'status-other';
                                if ($student['status'] === 'Completed') {
                                    $statusClass = 'status-completed';
                                } elseif ($student['status'] === 'Expired') {
                                     $statusClass = 'status-expired';
                                }
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['passcode']) ?></td>
                                    <td><?= htmlspecialchars($student['name']) ?></td>

                                    <?php
                                    // Loop through the defined subject order for consistent column display
                                    foreach ($subjectDisplayOrder as $subjectName):
                                        // Find the subject ID from the map (case-insensitive matching)
                                        $subjectId = $subjectNameToIdMap[trim(strtoupper($subjectName))] ?? null;
                                        $scoreDisplay = '-'; // Default display

                                        // Check if the subject ID exists and if the student has score info
                                        if ($subjectId !== null && isset($student['subject_scores'][$subjectId])) {
                                            // Display the 'score' field directly
                                            $scoreDisplay = $student['subject_scores'][$subjectId]['score'] ?? '-';
                                        }
                                    ?>
                                        <td class="score-cell"><?= htmlspecialchars($scoreDisplay) ?></td>
                                    <?php endforeach; ?>

                                    <td class="score-cell"><?= htmlspecialchars($student['total_score'] ?? '-') ?></td>
                                    <td>
                                        <?= $student['test_date'] ? date('M d, Y H:i', strtotime($student['test_date'])) : 'N/A' ?>
                                        <br><span class="<?= $statusClass ?>" style="font-size: 0.9em;"><?= htmlspecialchars($student['status']) ?></span>
                                    </td>
                                    <td class="actions-cell">
                                        <a href="/admin/reports/student_results.php?student_id=<?= htmlspecialchars($student['student_id']) ?>&attempt_id=<?= htmlspecialchars($student['attempt_id']) ?>" class="button" title="View Detailed Results for Attempt <?= htmlspecialchars($student['attempt_id']) ?>">View Details</a>
                                        <a href="/admin/reports/print_gc_form1.php?student_id=<?= htmlspecialchars($student['student_id']) ?>&attempt_id=<?= htmlspecialchars($student['attempt_id']) ?>" class="button button-print" target="_blank" title="Print GC Form 1 for <?= htmlspecialchars($student['name']) ?> (Attempt <?= htmlspecialchars($student['attempt_id']) ?>)">Print Form</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div> <!-- /.panel -->
    </div> <!-- /.container -->

    <!-- Footer partial if you have one -->
    <?php // require_once __DIR__ . '/../partials/admin_foot.php'; ?>

    <script>
        /**
         * Filters the student table based on input in the search box.
         * Matches against Passcode (column 0) and Name (column 1).
         */
        function filterStudents() {
            const input = document.getElementById("studentSearch");
            const filter = input.value.toUpperCase().trim();
            const table = document.getElementById("studentsTable");
            const tbody = table.getElementsByTagName("tbody")[0];
            const tr = tbody.getElementsByTagName("tr");

            // Loop through all table body rows
            for (let i = 0; i < tr.length; i++) {
                const tdPasscode = tr[i].getElementsByTagName("td")[0]; // Passcode column
                const tdName = tr[i].getElementsByTagName("td")[1];     // Name column
                let displayRow = false; // Assume row should be hidden initially

                // Check if filter matches Passcode
                if (tdPasscode) {
                    const txtValuePasscode = (tdPasscode.textContent || tdPasscode.innerText).trim();
                    if (txtValuePasscode.toUpperCase().indexOf(filter) > -1) {
                        displayRow = true;
                    }
                }
                // If not matched by Passcode, check if it matches Name
                if (!displayRow && tdName) {
                    const txtValueName = (tdName.textContent || tdName.innerText).trim();
                    if (txtValueName.toUpperCase().indexOf(filter) > -1) {
                         displayRow = true;
                    }
                }

                // Show or hide the row based on whether a match was found
                tr[i].style.display = displayRow ? "" : "none";
            }
        }

        /**
         * Exports the visible data in the table to a CSV file.
         * Excludes the "Actions" column.
         * @param {string} filename - The desired name for the CSV file.
         */
        function exportTableToCSV(filename) {
            let csv = [];
            const headerRow = document.querySelector("#studentsTable thead tr");
            const bodyRows = document.querySelectorAll("#studentsTable tbody tr");

            if (!headerRow) return; // No table header found

            const numCols = headerRow.querySelectorAll("th").length;
            if (numCols <= 1) return; // No data columns besides maybe actions

            // --- Get Headers ---
            let header = [];
            const ths = headerRow.querySelectorAll("th");
            // Iterate up to the second-to-last column (exclude Actions)
            for (let j = 0; j < numCols - 1; j++) {
                 // Get text content, clean up whitespace/newlines (especially if icons are present)
                 let cellText = ths[j].textContent.replace(/(\r\n|\n|\r)/gm, " ").replace(/\s+/g, ' ').trim();
                 // Escape double quotes within the header text and wrap in double quotes
                 header.push('"' + cellText.replace(/"/g, '""') + '"');
            }
            csv.push(header.join(",")); // Add header row to CSV array

            // --- Get Visible Body Rows ---
            bodyRows.forEach(function(row) {
                 // Only include rows that are currently visible (respects filtering)
                 if (row.style.display !== 'none') {
                     let rowData = [];
                     const cols = row.querySelectorAll("td");
                      // Iterate up to the second-to-last column (exclude Actions)
                     for (let j = 0; j < numCols - 1; j++) {
                        if (cols[j]) { // Check if cell exists
                            // Clean cell text (remove extra whitespace/newlines)
                            let cellText = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").replace(/\s+/g, ' ').trim();
                            // Escape double quotes and wrap in double quotes
                            rowData.push('"' + cellText.replace(/"/g, '""') + '"');
                        } else {
                            rowData.push('""'); // Add empty quoted string if cell is missing somehow
                        }
                     }
                     csv.push(rowData.join(",")); // Add data row to CSV array
                 }
            });

            // Trigger CSV Download
            if (csv.length > 1) { // Check if there's more than just the header
                downloadCSV(csv.join("\n"), filename);
            } else {
                alert("No data available to export (check filters).");
            }
        }

        /**
         * Creates a Blob and triggers a download link for the CSV data.
         * Includes BOM for UTF-8 compatibility.
         * @param {string} csv - The CSV data as a single string with newline separators.
         * @param {string} filename - The filename for the downloaded file.
         */
        function downloadCSV(csv, filename) {
            let csvFile;
            let downloadLink;

            // Byte Order Mark (BOM) to ensure UTF-8 compatibility, especially in Excel
            const BOM = "\uFEFF";

            // Create a Blob containing the CSV data with BOM
            csvFile = new Blob([BOM + csv], { type: "text/csv;charset=utf-8;" });

            // Create a temporary download link
            downloadLink = document.createElement("a");
            downloadLink.download = filename; // Set the filename
            downloadLink.href = window.URL.createObjectURL(csvFile); // Create a URL for the Blob
            downloadLink.style.display = "none"; // Hide the link

            // Append the link to the body, click it, then remove it
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
             // Optional: Revoke the object URL to free up memory, though modern browsers handle this well
             // window.URL.revokeObjectURL(downloadLink.href);
        }
    </script>

</body>
</html>