<?php
/**
 * Admin Analytics Controller
 * 
 * This file handles all the data processing for the analytics dashboard.
 * It retrieves and processes data from the database and prepares it for display.
 */

// Include bootstrap for database connection and common functions
require_once __DIR__ . '/../../bootstrap.php';

// Require admin login
requireAdminLogin();

// Initialize the data arrays
$topStudents = [];
$schoolPerformance = [];
$subjectPerformance = [];
$testAttemptTrends = [];
$recentExams = [];
$strandPerformance = [];
$overallStats = [];

try {
    // ----- Top performing students (with rank) -----
    // Modified to use attempt_scores_by_subject table for scores
    // Removed passcode from selection
    $topStudentsStatement = $db->query(
        "SELECT 
            s.student_id, 
            CONCAT(s.first_name, ' ', s.last_name) AS student_name,
            ta.total_score,
            sc.school_name,
            ta.created_at AS test_date,
            SUM(asbs.items_attempted) AS total_questions,
            ROUND((ta.total_score / SUM(asbs.items_attempted)) * 100, 2) AS percentage_score
         FROM 
            test_attempts ta
         JOIN 
            students s ON ta.student_id = s.student_id
         LEFT JOIN 
            attempt_scores_by_subject asbs ON ta.attempt_id = asbs.attempt_id
         LEFT JOIN 
            schools sc ON s.school_id = sc.id
         WHERE 
            ta.status = 'Completed'
         GROUP BY 
            ta.attempt_id
         ORDER BY 
            percentage_score DESC, ta.total_score DESC
         LIMIT 20"
    );
    $topStudents = $topStudentsStatement->fetchAll();

    // Add rank to each student
    foreach ($topStudents as $index => $student) {
        $topStudents[$index]['rank'] = $index + 1;
    }

    // ----- School performance analysis -----
    // Modified to use attempt_scores_by_subject table for score data
    $schoolPerformanceStatement = $db->query(
        "SELECT 
            sc.school_name,
            COUNT(DISTINCT s.student_id) AS total_students,
            AVG(ta.total_score) AS avg_score,
            MAX(ta.total_score) AS max_score,
            MIN(ta.total_score) AS min_score,
            AVG(s.gwa) AS avg_gwa,
            COUNT(DISTINCT CASE WHEN ta.status = 'Completed' THEN ta.attempt_id END) AS completed_tests
         FROM 
            schools sc
         JOIN 
            students s ON sc.id = s.school_id
         JOIN 
            test_attempts ta ON s.student_id = ta.student_id
         WHERE 
            ta.status = 'Completed'
         GROUP BY 
            sc.id
         HAVING 
            total_students >= 2
         ORDER BY 
            avg_score DESC"
    );
    $schoolPerformance = $schoolPerformanceStatement->fetchAll();

    // Add rank to each school
    foreach ($schoolPerformance as $index => $school) {
        $schoolPerformance[$index]['rank'] = $index + 1;
    }

    // ----- Subject performance analysis -----
    // Modified to use attempt_scores_by_subject table
    $subjectPerformanceStatement = $db->query(
        "SELECT 
            s.name AS subject_name,
            COUNT(DISTINCT q.id) AS total_questions,
            COUNT(DISTINCT asbs.attempt_id) AS total_attempts,
            SUM(asbs.items_correct) AS correct_answers,
            SUM(asbs.items_attempted) AS total_answers,
            ROUND((SUM(asbs.items_correct) / SUM(asbs.items_attempted)) * 100, 2) AS success_rate
         FROM 
            subjects s
         JOIN 
            questions q ON s.id = q.subject_id
         LEFT JOIN 
            attempt_scores_by_subject asbs ON s.id = asbs.subject_id
         GROUP BY 
            s.id
         ORDER BY 
            success_rate DESC"
    );
    $subjectPerformance = $subjectPerformanceStatement->fetchAll();

    // ----- Test attempt trends by day -----
    $testAttemptTrendsStatement = $db->query(
        "SELECT 
            DATE(created_at) AS test_date,
            COUNT(*) AS total_attempts,
            SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_attempts,
            SUM(CASE WHEN status = 'Expired' THEN 1 ELSE 0 END) AS expired_attempts
         FROM 
            test_attempts
         GROUP BY 
            DATE(created_at)
         ORDER BY 
            test_date DESC
         LIMIT 
            14"
    );
    $testAttemptTrends = $testAttemptTrendsStatement->fetchAll();
    // Reverse the array to show oldest date first
    if (!empty($testAttemptTrends)) {
        $testAttemptTrends = array_reverse($testAttemptTrends);
    }

    // ----- Recent exams -----
    // Modified to use attempt_scores_by_subject table for score data
    // Removed passcode from selection
    $recentExamsStatement = $db->query(
        "SELECT 
            ta.attempt_id,
            ta.created_at,
            ta.end_time,
            ta.status,
            ta.total_score,
            CONCAT(s.first_name, ' ', s.last_name) AS student_name,
            sc.school_name,
            SUM(asbs.items_attempted) AS total_questions,
            ROUND((ta.total_score / SUM(asbs.items_attempted)) * 100, 2) AS percentage_score
         FROM 
            test_attempts ta
         JOIN 
            students s ON ta.student_id = s.student_id
         LEFT JOIN 
            schools sc ON s.school_id = sc.id
         LEFT JOIN 
            attempt_scores_by_subject asbs ON ta.attempt_id = asbs.attempt_id
         WHERE 
            ta.status IN ('Completed', 'Expired')
         GROUP BY 
            ta.attempt_id
         ORDER BY 
            ta.created_at DESC
         LIMIT 
            10"
    );
    $recentExams = $recentExamsStatement->fetchAll();

    // ----- Overall stats -----
    // Modified to use relevant tables in your schema
    $statsStatement = $db->query(
        "SELECT
            (SELECT COUNT(*) FROM students) AS total_students,
            (SELECT COUNT(*) FROM test_attempts WHERE status = 'Completed') AS completed_exams,
            (SELECT COUNT(*) FROM schools) AS total_schools,
            (SELECT AVG(total_score) FROM test_attempts WHERE status = 'Completed') AS avg_score,
            (SELECT MAX(total_score) FROM test_attempts WHERE status = 'Completed') AS highest_score,
            (SELECT SUM(items_correct) FROM attempt_scores_by_subject) / 
                (SELECT SUM(items_attempted) FROM attempt_scores_by_subject) * 100 AS overall_correctness_rate
        "
    );
    $overallStats = $statsStatement->fetch();

    // ----- Strand Performance Analysis -----
    // Modified to match your schema
    $strandPerformanceStatement = $db->query(
        "SELECT 
            str.name AS strand_name,
            COUNT(DISTINCT s.student_id) AS total_students,
            AVG(ta.total_score) AS avg_score,
            MAX(ta.total_score) AS max_score,
            MIN(ta.total_score) AS min_score
         FROM 
            strands str
         JOIN 
            students s ON str.strand_id = s.strand_id
         JOIN 
            test_attempts ta ON s.student_id = ta.student_id
         WHERE 
            ta.status = 'Completed'
         GROUP BY 
            str.strand_id
         HAVING 
            total_students >= 2
         ORDER BY 
            avg_score DESC"
    );
    $strandPerformance = $strandPerformanceStatement->fetchAll();

} catch (PDOException $e) {
    // Log error and set error message
    error_log("Database Error in Analytics: " . $e->getMessage());
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Database error while retrieving analytics data: ' . $e->getMessage()];
}

// Get list of all schools for filter
try {
    $schoolsStatement = $db->query("SELECT id, school_name FROM schools ORDER BY school_name");
    $schools = $schoolsStatement->fetchAll();
} catch (PDOException $e) {
    $schools = [];
}

// Get list of all strands for filter
try {
    $strandsStatement = $db->query("SELECT strand_id, name FROM strands ORDER BY name");
    $strands = $strandsStatement->fetchAll();
} catch (PDOException $e) {
    $strands = [];
}

// Get list of all subjects
try {
    $subjectsStatement = $db->query("SELECT id, name FROM subjects ORDER BY name");
    $subjects = $subjectsStatement->fetchAll();
} catch (PDOException $e) {
    $subjects = [];
}

// Check if we need to add sample data for testing
// Set this to true if you want to generate sample data when no real data is present
$generateSampleData = true;

// Institution details for branding
$institutionName = "Isabela State University";
$campusName = "Roxas Campus";
$departmentName = "Guidance & Counseling Unit";

// After your data retrieval code (before including the view), add this check:
if ($generateSampleData && (empty($topStudents) || empty($schoolPerformance))) {
    // Generate sample data for demonstration purposes
    $sampleSchools = [
        ['id' => 1, 'school_name' => 'Springfield High School'],
        ['id' => 2, 'school_name' => 'Riverdale Academy'],
        ['id' => 3, 'school_name' => 'Westview College'],
        ['id' => 4, 'school_name' => 'Oakridge Institute'],
        ['id' => 5, 'school_name' => 'Central Tech'],
    ];
    
    $sampleSubjects = [
        ['id' => 1, 'name' => 'Mathematics'],
        ['id' => 2, 'name' => 'Science'],
        ['id' => 3, 'name' => 'English'],
        ['id' => 4, 'name' => 'Filipino'],
        ['id' => 5, 'name' => 'Social Science'],
    ];
    
    $sampleStrands = [
        ['strand_id' => 1, 'name' => 'STEM'],
        ['strand_id' => 2, 'name' => 'ABM'],
        ['strand_id' => 3, 'name' => 'HUMSS'],
        ['strand_id' => 4, 'name' => 'GAS'],
        ['strand_id' => 5, 'name' => 'TVL'],
    ];
    
    // Sample top students
    $topStudents = [];
    for ($i = 1; $i <= 10; $i++) {
        $schoolIndex = array_rand($sampleSchools);
        $score = rand(70, 98);
        $totalQuestions = 100;
        
        $topStudents[] = [
            'student_id' => $i,
            'student_name' => 'Student ' . $i,
            'total_score' => $score,
            'school_name' => $sampleSchools[$schoolIndex]['school_name'],
            'test_date' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')),
            'total_questions' => $totalQuestions,
            'percentage_score' => ($score / $totalQuestions) * 100,
            'rank' => $i
        ];
    }
    
    // Sort by score descending
    usort($topStudents, function($a, $b) {
        return $b['total_score'] - $a['total_score'];
    });
    
    // Reassign ranks
    foreach ($topStudents as $index => $student) {
        $topStudents[$index]['rank'] = $index + 1;
    }
    
    // Sample school performance
    $schoolPerformance = [];
    foreach ($sampleSchools as $index => $school) {
        $totalStudents = rand(5, 30);
        $avgScore = rand(70, 90) + (rand(0, 10) / 10);
        
        $schoolPerformance[] = [
            'school_name' => $school['school_name'],
            'total_students' => $totalStudents,
            'avg_score' => $avgScore,
            'max_score' => $avgScore + rand(5, 10),
            'min_score' => $avgScore - rand(5, 10),
            'avg_gwa' => rand(80, 95) + (rand(0, 10) / 10),
            'completed_tests' => $totalStudents * rand(1, 3),
            'rank' => $index + 1
        ];
    }
    
    // Sort by average score descending
    usort($schoolPerformance, function($a, $b) {
        return $b['avg_score'] - $a['avg_score'];
    });
    
    // Reassign ranks
    foreach ($schoolPerformance as $index => $school) {
        $schoolPerformance[$index]['rank'] = $index + 1;
    }
    
    // Sample subject performance
    $subjectPerformance = [];
    foreach ($sampleSubjects as $subject) {
        $totalQuestions = rand(20, 50);
        $totalStudents = rand(20, 100);
        $correctAnswers = $totalQuestions * $totalStudents * (rand(50, 90) / 100);
        $totalAnswers = $totalQuestions * $totalStudents;
        
        $subjectPerformance[] = [
            'subject_name' => $subject['name'],
            'total_questions' => $totalQuestions,
            'total_attempts' => $totalStudents,
            'correct_answers' => $correctAnswers,
            'total_answers' => $totalAnswers,
            'success_rate' => ($correctAnswers / $totalAnswers) * 100
        ];
    }
    
    // Sample strand performance
    $strandPerformance = [];
    foreach ($sampleStrands as $strand) {
        $totalStudents = rand(5, 30);
        $avgScore = rand(70, 90) + (rand(0, 10) / 10);
        
        $strandPerformance[] = [
            'strand_name' => $strand['name'],
            'total_students' => $totalStudents,
            'avg_score' => $avgScore,
            'max_score' => $avgScore + rand(5, 10),
            'min_score' => $avgScore - rand(5, 10)
        ];
    }
    
    // Sample test attempt trends
    $testAttemptTrends = [];
    for ($i = 13; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime('-' . $i . ' days'));
        $totalAttempts = rand(5, 25);
        $completedAttempts = rand(3, $totalAttempts);
        $expiredAttempts = $totalAttempts - $completedAttempts;
        
        $testAttemptTrends[] = [
            'test_date' => $date,
            'total_attempts' => $totalAttempts,
            'completed_attempts' => $completedAttempts,
            'expired_attempts' => $expiredAttempts
        ];
    }
    
    // Sample recent exams
    $recentExams = [];
    for ($i = 1; $i <= 10; $i++) {
        $schoolIndex = array_rand($sampleSchools);
        $score = rand(70, 98);
        $totalQuestions = 100;
        $status = ['Completed', 'Expired'][rand(0, 1)];
        
        $recentExams[] = [
            'attempt_id' => $i,
            'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 10) . ' days')),
            'end_time' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 10) . ' days +2 hours')),
            'status' => $status,
            'total_score' => $score,
            'student_name' => 'Student ' . $i,
            'school_name' => $sampleSchools[$schoolIndex]['school_name'],
            'total_questions' => $totalQuestions,
            'percentage_score' => ($score / $totalQuestions) * 100
        ];
    }
    
    // For overall stats
    $overallStats = [
        'total_students' => 150,
        'completed_exams' => 120,
        'total_schools' => count($sampleSchools),
        'avg_score' => 82.5,
        'highest_score' => 98,
        'overall_correctness_rate' => 76.3
    ];
    
    // Also add sample schools for the filter dropdown
    $schools = $sampleSchools;
    $strands = $sampleStrands;
    $subjects = $sampleSubjects;
}

// Set the page title
$title = "Analytics Dashboard - $institutionName $campusName ECAT System";

// Include the view file
require_once __DIR__ . '/views/index.view.php';