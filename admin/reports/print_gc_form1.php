<?php
// File: admin/reports/print_gc_form1.php

require_once __DIR__ . '/../../bootstrap.php'; // Adjust path as needed

// --- Authentication ---
requireAdminLogin();

// --- Database Connection ---
// Ensure $db is available (initialized in bootstrap.php or here)
if (!isset($db)) {
    try {
        $db = new Database($config['database']);
    } catch (PDOException $e) {
        error_log("Database Connection Error: " . $e->getMessage());
        die("Error connecting to the database.");
    }
}

// --- Get Student ID ---
$student_id = $_GET['student_id'] ?? null;

if (!$student_id || !filter_var($student_id, FILTER_VALIDATE_INT)) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Invalid Student ID.'];
    header('Location: /admin/dashboard.php'); // Or back to student list
    exit;
}

// --- Fetch Student Data (Keep your existing query) ---
$query_student = "
    SELECT
        s.*, -- Select all from students
        c1.course_name as first_preference_name,
        c2.course_name as second_preference_name,
        sch.school_name as school_attended_name,
        sch.address as school_address, /* Added school address */
        str.name as strand_name,
        b.name as barangay_name,
        m.name as municipality_name,
        p.name as province_name,
        m.zip_code
    FROM
        students s
    LEFT JOIN courses c1 ON s.first_preference_id = c1.course_id
    LEFT JOIN courses c2 ON s.second_preference_id = c2.course_id
    LEFT JOIN schools sch ON s.school_id = sch.id
    LEFT JOIN strands str ON s.strand_id = str.strand_id
    LEFT JOIN barangays b ON s.barangay_id = b.barangay_id
    LEFT JOIN municipalities m ON s.municipality_id = m.municipality_id
    LEFT JOIN provinces p ON s.province_id = p.province_id
    WHERE
        s.student_id = :student_id
";

try {
    $statement_student = $db->query($query_student, ['student_id' => $student_id]);
    $student = $statement_student->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Error fetching student data: " . $e->getMessage());
    die("Database Error: Could not fetch student data.");
}

if (!$student) {
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Student not found.'];
    header('Location: /admin/dashboard.php'); // Or back to student list
    exit;
}

// --- Fetch Latest Completed/Expired Test Attempt and Scores ---
$latestAttempt = null;
$subjectScoresMap = []; // To store scores mapped by subject name
$totalScore = '-'; // Default value
$relevantAttemptId = null;

try {
    // Find the latest completed or expired attempt for this student
    $query_attempt = "
        SELECT attempt_id, total_score, status
        FROM test_attempts
        WHERE student_id = :student_id
          AND status IN ('Completed', 'Expired')
        ORDER BY created_at DESC
        LIMIT 1";
    $statement_attempt = $db->query($query_attempt, ['student_id' => $student_id]);
    $latestAttempt = $statement_attempt->fetch(PDO::FETCH_ASSOC);

    if ($latestAttempt) {
        $relevantAttemptId = $latestAttempt['attempt_id'];
        $totalScore = $latestAttempt['total_score'] ?? '-'; // Use score from attempt table

        // Fetch subject scores for this attempt
        $query_scores = "
            SELECT
                s.name AS subject_name,
                asbs.score
            FROM attempt_scores_by_subject asbs
            JOIN subjects s ON asbs.subject_id = s.id
            WHERE asbs.attempt_id = :attempt_id";
        $statement_scores = $db->query($query_scores, ['attempt_id' => $relevantAttemptId]);
        $subjectScoresRaw = $statement_scores->fetchAll(PDO::FETCH_ASSOC);

        // Map scores by subject name (case-insensitive and trimmed)
        foreach ($subjectScoresRaw as $scoreData) {
             $subjectNameNormalized = trim(strtoupper($scoreData['subject_name']));
             $subjectScoresMap[$subjectNameNormalized] = $scoreData['score'];
        }
    }

} catch (PDOException $e) {
    error_log("Database Error fetching attempt/score data: " . $e->getMessage());
    // Don't die, just proceed without scores, maybe set a flash message?
    // $_SESSION['flash_message'] = ['type' => 'warning', 'message' => 'Could not load test scores.'];
    $totalScore = 'Error';
}

// --- Define Subject Order for Display ---
$subjectDisplayOrder = [
    'ENGLISH',
    'SCIENCE',
    'MATHEMATICS', // Match the exact name/case used as keys in $subjectScoresMap
    'SOCIAL SCIENCE',
    'FILIPINO',
    'ABSTRACT REASONING'
];


// --- Helper function for cleaner output ---
// Ensure e() is defined (e.g., in bootstrap.php or functions.php)
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}


// --- Format Student Data (Existing formatting) ---
$fullName = e($student['last_name']) . ', ' . e($student['first_name']) . ' ' . e($student['middle_name'] ?? '');
$fullNameForPic = e($student['last_name']) . ', ' . e($student['first_name']) . ' ' . e($student['middle_name'] ?? ''); // For picture box
$homeAddress = e($student['purok'] ?? '') . ' ' . e($student['barangay_name'] ?? '') . ', ' . e($student['municipality_name'] ?? '') . ', ' . e($student['province_name'] ?? '');
$homeAddress = trim(str_replace(' ,', ',', $homeAddress), ', '); // Clean up extra commas/spaces

$birthdayFormatted = $student['birthday'] ? date('F j, Y', strtotime($student['birthday'])) : '';
$enrollmentStatus = e($student['enrollment_status']);
$isFreshman = ($enrollmentStatus === 'Freshman');
$isTransferee = ($enrollmentStatus === 'Transferee');
$isSecondCourse = ($enrollmentStatus === 'Second Course');
// Simplified 'Others' - assumes if not the main 3, it's others. Adjust if needed.
$isOther = (!$isFreshman && !$isTransferee && !$isSecondCourse && !empty($enrollmentStatus));
$otherText = $isOther ? $enrollmentStatus : ''; // Display status if 'Others'

$isMale = (e($student['sex']) === 'Male');
$isFemale = (e($student['sex']) === 'Female');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print GC Form 1 - <?= e($fullName) ?></title>
    <style>
        /* --- YOUR EXISTING CSS FROM THE PREVIOUS RESPONSE --- */
        /* (Keep the CSS from the previous 'print_gc_form1.php' example here) */
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt; /* Adjust font size as needed */
            margin: 0;
            padding: 0;
            background-color: #fff; /* Ensure white background for printing */
        }
        .print-button-container {
            text-align: center;
            padding: 20px;
        }
        .print-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
        }
         .print-button:hover {
            background-color: #45a049;
         }
        .form-container {
            width: 8.5in; /* Standard Letter size width */
            min-height: 11in; /* Standard Letter size height */
            padding: 0.75in; /* Adjust margins */
            margin: 0 auto; /* Center on screen */
            border: 1px solid #ccc; /* Optional border for screen view */
            background-color: #fff;
            box-sizing: border-box;
            position: relative; /* Needed for absolute positioning of GC Form 1 box */
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start; /* Align items to the top */
            margin-bottom: 15px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .header-left img, .header-right img {
            max-height: 60px; /* Adjust logo size */
            max-width: 60px;
        }
         .header-center {
            text-align: center;
            line-height: 1.2;
            flex-grow: 1; /* Allow center to take available space */
            margin: 0 20px; /* Space around center text */
        }
        .header-center h2, .header-center h3, .header-center h4 {
             margin: 0;
             font-weight: normal; /* Match form style */
        }
         .header-center h2 { font-size: 11pt; font-weight: bold; }
         .header-center h3 { font-size: 10pt; font-weight: bold; }
         .header-center h4 { font-size: 9pt; font-weight: bold; }


        .form-title {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .form-section {
            margin-bottom: 15px;
            border: 1px solid #aaa;
            padding: 8px;
        }
         .form-section-title {
             font-weight: bold;
             margin-bottom: 8px;
             font-size: 10pt;
         }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        td, th { /* Apply padding to th as well */
            padding: 3px 5px;
            vertical-align: bottom; /* Align text to the bottom near the line */
            line-height: 1.4; /* Add space between lines */
        }
        .label {
            font-weight: normal; /* Labels are not bold */
             font-size: 9pt;
             white-space: nowrap; /* Prevent labels wrapping */
        }
        .data {
            border-bottom: 1px solid #000;
            font-weight: bold; /* Data often printed bolder */
            min-width: 150px; /* Ensure space for data */
            padding-left: 5px;
        }
         .short-data { min-width: 50px; }
         .medium-data { min-width: 100px; }
         .long-data { min-width: 250px; }
         .full-width-data { width: 100%; }
         .no-min-width { min-width: auto; } /* For checkbox area */

        .checkbox-label {
            margin-right: 15px;
            white-space: nowrap;
        }
        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            margin-right: 3px;
            vertical-align: middle;
            position: relative; /* For positioning the checkmark */
            top: -1px; /* Align baseline */
        }
         .checkbox.checked::before {
             content: 'X'; /* Or use ✓ */
             display: block;
             text-align: center;
             line-height: 10px; /* Match height */
             font-weight: bold;
             font-size: 10px; /* Match size */
             position: absolute;
             left: 0;
             top: 0;
         }
        .other-input {
            border-bottom: 1px solid black;
            display: inline-block;
            min-width: 100px;
            font-weight: bold;
            padding: 0 3px;
        }


        .picture-box {
            width: 1.8in; /* Slightly less than 2in for padding */
            height: 1.8in;
            border: 1px solid #000;
            float: right;
            text-align: center;
            padding: 5px;
            margin-left: 20px;
             font-size: 9pt;
             display: flex;
             flex-direction: column;
             justify-content: center; /* Center vertically */
             align-items: center; /* Center horizontally */
             margin-top: -50px; /* Adjust to align vertically - may need tuning */
             box-sizing: border-box;
        }
        .picture-box span { display: block; margin-bottom: 5px; line-height: 1.2; }
        .picture-name {
            font-size: 8pt;
            border-top: 1px solid #000;
            margin-top: auto; /* Pushes to the bottom */
            padding-top: 3px;
            width: 100%;
            word-wrap: break-word; /* Wrap long names */
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px; /* Space above signature line */
            text-align: center;
            font-size: 9pt;
            padding-top: 3px;
        }
        .clear { clear: both; } /* Clear float */

        .results-table th, .results-table td {
            border: 1px solid #000;
            text-align: center;
            padding: 4px;
            font-size: 9pt;
        }
        .results-table th { background-color: #eee; font-weight: bold;}
        /* Ensure remarks cell allows more space if needed */
        .results-table td:last-child { text-align: left; }

         .footer {
             margin-top: 30px;
             display: flex;
             justify-content: space-between;
             align-items: flex-end; /* Align items at the bottom */
             font-size: 8pt;
             border-top: 1px solid #000;
             padding-top: 5px;
         }
         .footer-left, .footer-right {
             width: 45%;
             line-height: 1.3; /* Adjust line spacing in footer */
         }
         .footer-right {
             text-align: right;
         }
         .footer-date-line {
              border-bottom: 1px solid #000;
              display: inline-block;
              min-width: 150px;
              margin-left: 5px; /* Space after 'Date' */
         }


         .form-id-box {
            border: 1px solid #000;
            padding: 2px 5px;
            display: inline-block; /* Make it wrap the text */
            position: absolute; /* Position relative to container */
            top: 0.5in; /* Adjust positioning */
            left: 0.75in; /* Align with left padding */
            font-size: 9pt;
         }

        /* Print-specific styles */
        @media print {
            .print-button-container {
                display: none; /* Hide print button when printing */
            }
            .form-container {
                border: none; /* No border when printing */
                margin: 0;
                padding: 0; /* Reset padding, use @page margin */
                width: 100%;
                height: auto;
                box-shadow: none;
            }
            body {
                margin: 0;
                 background-color: #fff !important; /* Ensure white background */
                 color: #000 !important; /* Ensure black text */
                 -webkit-print-color-adjust: exact !important; /* Force background/colors in Chrome/Safari */
                 print-color-adjust: exact !important;
            }
             .results-table th {
                 background-color: #eee !important; /* Ensure background prints */
                 color: #000 !important;
             }
             /* Ensure borders print */
             td, th, .picture-box, .form-id-box, .form-section, .signature-line, .footer, .data, .other-input, .footer-date-line {
                 border-color: #000 !important;
             }

            @page {
                size: Letter; /* Or A4 */
                margin: 0.75in; /* Adjust printable area margins */
            }
            /* Avoid page breaks inside sections if possible */
            .form-section, table, .picture-box, .form-header {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="print-button-container">
        <button class="print-button" onclick="window.print();">Print Form</button>
    </div>

    <div class="form-container">

        <div class="form-id-box">GC Form 1</div>

        <div class="form-header">
            <div class="header-left">
                <!-- Make sure paths are correct -->
                <img src="/assets/img/isu_logo.png" alt="ISU Logo">
            </div>
            <div class="header-center">
                Republic of the Philippines<br>
                <h2>ISABELA STATE UNIVERSITY</h2>
                <h3>ROXAS CAMPUS</h3>
                <h4>GUIDANCE & COUNSELING UNIT</h4>
                <span class="form-title">ENTRANCE EXAM FORM</span>
            </div>
            <div class="header-right">
                 <img src="/assets/img/roxas_logo.png" alt="Roxas Campus Logo">
            </div>
        </div>

        <div class="picture-box">
            <span>Place 2X2 picture here</span>
            <span>(White background with complete name tag)</span>
            <div class="picture-name"><?= e($fullNameForPic) ?></div>
            <!-- Optional: Student Photo Display -->
            <!-- <img src="<?= e($student['photo_path'] ?? '/assets/img/placeholder.png') ?>" alt="Student Photo" style="width:100%; height: auto; margin-top: 5px;"> -->
        </div>

        <div class="instruction label"><strong>PLEASE PRINT</strong></div>

        <!-- Personal Information Table -->
        <table>
             <!-- Row 1: Name -->
             <tr>
                 <td class="label">Name:</td>
                 <td class="data long-data"><?= e($student['last_name']) ?></td>
                 <td class="data long-data"><?= e($student['first_name']) ?></td>
                 <td class="data medium-data"><?= e($student['middle_name'] ?? '') ?></td>
             </tr>
             <!-- Row 2: Name Labels -->
             <tr>
                 <td></td> <!-- Spacer -->
                 <td class="label" style="border-top: 1px solid #999; text-align: center; font-size: 8pt;">Last Name</td>
                 <td class="label" style="border-top: 1px solid #999; text-align: center; font-size: 8pt;">First Name</td>
                 <td class="label" style="border-top: 1px solid #999; text-align: center; font-size: 8pt;">Middle Name</td>
             </tr>
             <!-- Row 3: Course Preferences & Strand -->
             <tr>
                 <td class="label">Course: First Preference:</td>
                 <td class="data" colspan="3"><?= e($student['first_preference_name'] ?? '') ?></td>
             </tr>
              <tr>
                 <td class="label">Second Preference:</td>
                 <td class="data" colspan="1"><?= e($student['second_preference_name'] ?? '') ?></td>
                 <td class="label">Track/Strand Taken:</td>
                 <td class="data no-min-width"><?= e($student['strand_name'] ?? '') ?></td>
             </tr>
              <!-- Row 4: Enrollment Status -->
              <tr>
                 <td class="label">Enrolment Status:</td>
                 <td colspan="3" class="no-min-width">
                     <span class="checkbox-label">
                         <span class="checkbox <?= $isFreshman ? 'checked' : '' ?>"></span> Freshman
                     </span>
                     <span class="checkbox-label">
                         <span class="checkbox <?= $isTransferee ? 'checked' : '' ?>"></span> Transferee
                     </span>
                     <span class="checkbox-label">
                         <span class="checkbox <?= $isSecondCourse ? 'checked' : '' ?>"></span> Second Course
                     </span>
                     <span class="checkbox-label">
                         <span class="checkbox <?= $isOther ? 'checked' : '' ?>"></span> Others: <span class="other-input"><?= e($otherText) ?> </span>
                     </span>
                 </td>
             </tr>
             <!-- Row 5 & 6: School -->
             <tr>
                 <td class="label">School Last Attended:</td>
                 <td class="data" colspan="3"><?= e($student['school_attended_name'] ?? '') ?></td>
             </tr>
             <tr>
                 <td class="label">School Address:</td>
                 <td class="data" colspan="3"><?= e($student['school_address'] ?? '') ?></td>
            </tr>
             <!-- Row 7: LRN & GWA -->
             <tr>
                 <td class="label">Learner's Reference Number:</td>
                 <td class="data medium-data"><?= e($student['lrn'] ?? '') ?></td>
                 <td class="label">GWA:</td>
                 <td class="data short-data"><?= e($student['gwa'] ?? '') ?></td>
             </tr>
             <!-- Row 8: Address -->
             <tr>
                <td class="label">Home Address:</td>
                <td class="data" colspan="2"><?= e($homeAddress) ?></td>
                <td class="label">Zip Code: <span class="data short-data no-min-width"><?= e($student['zip_code'] ?? '') ?></span></td>
            </tr>
             <!-- Row 9: Sex, Birthday, Email, Contact -->
              <tr>
                 <td class="label">Sex:</td>
                 <td class="no-min-width">
                     <span class="checkbox-label"><span class="checkbox <?= $isMale ? 'checked' : '' ?>"></span> Male</span>
                     <span class="checkbox-label"><span class="checkbox <?= $isFemale ? 'checked' : '' ?>"></span> Female</span>
                 </td>
                 <td class="label">Birthday: <span class="data medium-data no-min-width"><?= e($birthdayFormatted) ?></span></td>
                 <td class="label">Email Address: <span class="data medium-data no-min-width"><?= e($student['email'] ?? '') ?></span></td>
             </tr>
             <tr>
                <td class="label">Contact #:</td>
                <td class="data medium-data" colspan="3"><?= e($student['contact_number'] ?? '') ?></td>
             </tr>
        </table>

         <div class="signature-line">Student Signature Over Printed Name</div>

        <div class="clear"></div> <!-- Clear float from picture box -->

        <!-- Entrance Test Schedule Section -->
        <div class="form-section">
            <div class="form-section-title">Entrance Test Schedule: (for testing personnel only)</div>
             <table>
                 <tr>
                     <td class="label">Date of Examination:</td>
                     <td class="data medium-data"> </td> <!-- Placeholder -->
                     <td class="label">Time:</td>
                     <td class="data short-data"> </td> <!-- Placeholder -->
                 </tr>
                 <tr>
                     <td class="label">Venue:</td>
                     <td class="data medium-data"> </td> <!-- Placeholder -->
                    <td colspan="2"></td>
                 </tr>
                 <tr>
                     <td class="label">OR No.</td>
                     <td class="data medium-data"> </td> <!-- Placeholder -->
                     <td class="label">Scheduled by:</td>
                     <td class="data medium-data"> </td> <!-- Placeholder -->
                 </tr>
             </table>
        </div>

        <!-- Entrance Test Result Section - POPULATED -->
        <div class="form-section">
             <div class="form-section-title">Entrance Test Result (for Guidance and Counseling Unit personnel only)</div>
             <table class="results-table">
                 <thead>
                     <tr>
                         <th>Score:</th> <!-- This header seems redundant if scores are below, maybe remove or repurpose? -->
                         <?php foreach ($subjectDisplayOrder as $subjectName): ?>
                             <th><?= e(ucwords(strtolower(str_replace('_', ' ', $subjectName)))) // Format display name ?></th>
                         <?php endforeach; ?>
                         <th>Total Score</th>
                         <th>Remarks:</th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td> </td> <!-- Empty cell under "Score:" header -->
                         <?php
                         // Loop through the defined subject order to display scores
                         foreach ($subjectDisplayOrder as $subjectNameKey):
                             // SubjectNameKey is already normalized (UPPERCASE) from array definition
                             $score = $subjectScoresMap[$subjectNameKey] ?? '-'; // Get score or default
                         ?>
                             <td><?= e($score) ?></td>
                         <?php endforeach; ?>

                         <td><?= e($totalScore) ?></td>
                         <td> </td> <!-- Remarks placeholder -->
                    </tr>
                 </tbody>
             </table>

             <table>
                 <tr>
                     <td class="label">Issued by:</td>
                     <td class="data long-data"> </td> <!-- Placeholder -->
                     <td class="label" style="text-align: right; width: 40%;">Guidance Counselor / Guidance Personnel</td>
                 </tr>
                  <tr>
                     <td></td> <!-- Spacer -->
                     <td style="border-top: 1px solid #000;"></td>
                     <td style="border-top: 1px solid #000;"></td>
                 </tr>
             </table>
        </div>

         <div class="footer">
             <div class="footer-left">
                 ISUR-OSS-CAR-034<br>
                 Effectivity: May 03, 2024<br>
                 Rev. 02
             </div>
             <div class="footer-right">
                 <span class="label">Date</span>
                 <span class="footer-date-line"> </span>
             </div>
         </div>

    </div> <!-- /form-container -->

</body>
</html>