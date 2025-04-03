<?php
// Data export page (admin/reports/export.php)
require_once __DIR__ . '/../../bootstrap.php';

// Require admin login
requireAdminLogin();

$title = "Export Data - Admin Dashboard";
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
        
        .export-option {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .export-option h3 {
            margin-top: 0;
            color: #2196F3;
        }
        
        .export-option p {
            margin-bottom: 15px;
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
    </style>
</head>
<body>
    <header>
        <h1 class="welcome">Export Data</h1>
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
        
        <div class="panel">
            <h2>Export Data</h2>
            
            <div class="export-option">
                <h3>Export Student Results</h3>
                <p>Export a CSV file containing all student exam results, including scores by subject.</p>
                <form method="get" action="#">
                    <button type="submit" name="export" value="student_results" class="button">Export Student Results</button>
                </form>
            </div>
            
            <div class="export-option">
                <h3>Export Student Information</h3>
                <p>Export a CSV file containing student profile information.</p>
                <form method="get" action="#">
                    <button type="submit" name="export" value="student_info" class="button">Export Student Information</button>
                </form>
            </div>
            
            <div class="export-option">
                <h3>Export Question Analysis</h3>
                <p>Export a CSV file containing analysis of question difficulty based on student responses.</p>
                <form method="get" action="#">
                    <button type="submit" name="export" value="question_analysis" class="button">Export Question Analysis</button>
                </form>
            </div>
            
            <p><a href="/admin/reports/student_results.php">View Exam Results</a> instead of exporting.</p>
        </div>
    </div>
</body>
</html>