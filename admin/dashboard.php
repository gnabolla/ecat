<?php
// Admin dashboard page (admin/dashboard.php)
require_once __DIR__ . '/../bootstrap.php';

// Require login
requireAdminLogin();

// Get some dashboard statistics
$studentCountStatement = $db->query("SELECT COUNT(*) as count FROM students");
$studentCount = $studentCountStatement->fetch()['count'];

$testAttemptsStatement = $db->query("SELECT COUNT(*) as count FROM test_attempts");
$testAttemptsCount = $testAttemptsStatement->fetch()['count'];

$completedTestsStatement = $db->query("SELECT COUNT(*) as count FROM test_attempts WHERE status = 'Completed'");
$completedTestsCount = $completedTestsStatement->fetch()['count'];

$title = "Admin Dashboard - ECAT System";
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

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .stat-card h2 {
            font-size: 36px;
            margin: 10px 0;
            color: #333;
        }

        .stat-card p {
            color: #666;
            margin: 0;
            font-size: 14px;
        }

        .management-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .management-section h2 {
            color: #333;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .management-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
        }

        .admin-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            height: 110px;
        }

        .admin-btn:hover {
            background-color: #2196F3;
            color: white;
            border-color: #2196F3;
        }

        .admin-btn i {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .admin-btn .btn-title {
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
        }

        .admin-btn .btn-desc {
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .admin-btn:hover .btn-desc {
            color: rgba(255, 255, 255, 0.8);
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

        /* Basic icon classes since we're not using a full icon library */
        .icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            background-color: currentColor;
            -webkit-mask-size: cover;
            mask-size: cover;
            margin-bottom: 10px;
        }

        .icon-users {
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E");
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E");
        }

        .icon-test {
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z'/%3E%3C/svg%3E");
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z'/%3E%3C/svg%3E");
        }

        .icon-reports {
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z'/%3E%3C/svg%3E");
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z'/%3E%3C/svg%3E");
        }

        .icon-settings {
            -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z'/%3E%3C/svg%3E");
            mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath d='M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z'/%3E%3C/svg%3E");
        }
    </style>
</head>

<body>
    <header>
        <h1 class="welcome">Admin Dashboard</h1>
        <div class="user-info">
            <div class="user-role"><?= htmlspecialchars($_SESSION['admin_role']) ?></div>
            <div class="logout">
                <a href="/admin/logout.php">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <?php flashMessage(); ?>

        <div class="dashboard-grid">
            <div class="stat-card">
                <p>Total Students</p>
                <h2><?= number_format($studentCount) ?></h2>
            </div>

            <div class="stat-card">
                <p>Total Test Attempts</p>
                <h2><?= number_format($testAttemptsCount) ?></h2>
            </div>

            <div class="stat-card">
                <p>Completed Tests</p>
                <h2><?= number_format($completedTestsCount) ?></h2>
            </div>

            <div class="stat-card">
                <p>Completion Rate</p>
                <h2><?= $testAttemptsCount > 0 ? number_format(($completedTestsCount / $testAttemptsCount) * 100, 1) : 0 ?>%</h2>
            </div>
        </div>

        <!-- <div class="management-section">
            <h2>Student Management</h2>
            <div class="management-grid">
                <a href="/admin/students/index.php" class="admin-btn">
                    <span class="icon icon-users"></span>
                    <div class="btn-title">View All Students</div>
                    <div class="btn-desc">Manage student records</div>
                </a>
                <a href="/admin/students/add.php" class="admin-btn">
                    <span class="icon icon-users"></span>
                    <div class="btn-title">Add New Student</div>
                    <div class="btn-desc">Create new student account</div>
                </a>
            </div>
        </div>
        
        <div class="management-section">
            <h2>Test Management</h2>
            <div class="management-grid">
                <a href="/admin/questions/index.php" class="admin-btn">
                    <span class="icon icon-test"></span>
                    <div class="btn-title">Manage Questions</div>
                    <div class="btn-desc">Edit test questions</div>
                </a>
                <a href="/admin/questions/add.php" class="admin-btn">
                    <span class="icon icon-test"></span>
                    <div class="btn-title">Add New Question</div>
                    <div class="btn-desc">Create new test question</div>
                </a>
            </div>
        </div> -->

        <!-- Add this to your admin/dashboard.php file -->
        <!-- This code adds a button to access the analytics dashboard -->

        <div class="management-section">
            <h2>Analytics & Reports</h2>
            <div class="management-grid">
                <a href="/admin/analytics/index.php" class="admin-btn">
                    <span class="icon icon-reports"></span>
                    <div class="btn-title">Analytics Dashboard</div>
                    <div class="btn-desc">Performance insights</div>
                </a>
                <a href="/admin/reports/all_test_results.php" class="admin-btn">
                    <span class="icon icon-reports"></span>
                    <div class="btn-title">Test Results</div>
                    <div class="btn-desc">View all student results</div>
                </a>
                <a href="/admin/reports/export.php" class="admin-btn">
                    <span class="icon icon-reports"></span>
                    <div class="btn-title">Export Data</div>
                    <div class="btn-desc">Download system data</div>
                </a>
            </div>
        </div>

        <!-- <?php if ($_SESSION['admin_role'] === 'Administrator'): ?>
        <div class="management-section">
            <h2>System Administration</h2>
            <div class="management-grid">
                <a href="/admin/users/index.php" class="admin-btn">
                    <span class="icon icon-settings"></span>
                    <div class="btn-title">Manage Users</div>
                    <div class="btn-desc">Admin user accounts</div>
                </a>
                <a href="/admin/settings.php" class="admin-btn">
                    <span class="icon icon-settings"></span>
                    <div class="btn-title">System Settings</div>
                    <div class="btn-desc">Configure system options</div>
                </a>
            </div>
        </div>
        <?php endif; ?> -->
    </div>
</body>

</html>