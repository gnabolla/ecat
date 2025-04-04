<?php
/**
 * Admin Analytics View
 * 
 * This file contains the HTML structure for the analytics dashboard.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/admin/analytics/assets/css/analytics.css">
    <style>
        /* ISU Roxas specific styles */
        :root {
            --primary-color: #006400; /* ISU Green */
            --primary-light: #008000;
            --primary-dark: #004d00;
            --secondary-color: #FFD700; /* Gold accent */
            --secondary-light: #FFF8DC;
            --secondary-dark: #DAA520;
        }

        .sidebar-header {
            background-color: var(--primary-dark);
            padding: 20px;
            text-align: center;
        }

        .institution-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 15px;
        }

        .institution-logo img {
            max-height: 70px;
            margin: 0 10px;
        }

        .institution-info {
            text-align: center;
            color: white;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 10px;
            margin-top: 10px;
        }

        .institution-info h3 {
            margin: 5px 0;
            font-size: 16px;
        }

        .institution-info p {
            margin: 5px 0;
            font-size: 12px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="analytics-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="institution-logo">
                    <!-- You can add actual logos here -->
                    <img src="/assets/img/isu_logo.png" alt="ISU Logo" onerror="this.src='/admin/analytics/assets/img/isu_logo.png'">
                </div>
                <div class="institution-info">
                    <h3><?= htmlspecialchars($institutionName) ?></h3>
                    <h3><?= htmlspecialchars($campusName) ?></h3>
                    <p><?= htmlspecialchars($departmentName) ?></p>
                </div>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="/admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
                    <li class="active"><a href="#"><i class="fas fa-chart-bar"></i> <span>Analytics</span></a></li>
                    <li><a href="/admin/reports/all_test_results.php"><i class="fas fa-file-alt"></i> <span>Reports</span></a></li>
                    <li><a href="/admin/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <p>Logged in as: <?= htmlspecialchars($_SESSION['admin_role'] ?? 'Administrator') ?></p>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Header with user info -->
            <header class="main-header">
                <div class="header-title">
                    <h1>ECAT Analytics Dashboard</h1>
                    <p>Comprehensive insights into student performance</p>
                </div>
                <div class="user-info">
                    <span class="user-name"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator') ?></span>
                    <a href="/admin/logout.php" class="logout-btn">Logout</a>
                </div>
            </header>

            <!-- Flash Messages -->
            <?php flashMessage(); ?>

            <!-- Stats Cards Section -->
            <section class="stats-cards">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-user-graduate"></i></div>
                    <div class="stat-details">
                        <h3>Total Students</h3>
                        <p class="stat-value"><?= number_format($overallStats['total_students'] ?? 0) ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clipboard-check"></i></div>
                    <div class="stat-details">
                        <h3>Completed Exams</h3>
                        <p class="stat-value"><?= number_format($overallStats['completed_exams'] ?? 0) ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-school"></i></div>
                    <div class="stat-details">
                        <h3>Participating Schools</h3>
                        <p class="stat-value"><?= number_format($overallStats['total_schools'] ?? 0) ?></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                    <div class="stat-details">
                        <h3>Highest Score</h3>
                        <p class="stat-value"><?= number_format($overallStats['highest_score'] ?? 0) ?></p>
                    </div>
                </div>
            </section>

            <!-- Charts Section -->
            <section class="charts-section">
                <div class="chart-container">
                    <div class="chart-header">
                        <h2>Exam Attempts Trend</h2>
                        <div class="chart-actions">
                            <button class="refresh-btn" onclick="refreshChart('attempts-chart')"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="attempts-chart"></canvas>
                    </div>
                </div>
                
                <div class="chart-container">
                    <div class="chart-header">
                        <h2>Subject Performance</h2>
                        <div class="chart-actions">
                            <button class="refresh-btn" onclick="refreshChart('subjects-chart')"><i class="fas fa-sync-alt"></i></button>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="subjects-chart"></canvas>
                    </div>
                </div>
            </section>

            <!-- Gamified Leaderboard Section -->
            <section class="leaderboard-section">
                <div class="leaderboard-container">
                    <div class="leaderboard-header">
                        <h2><i class="fas fa-medal"></i> Top Students Leaderboard</h2>
                        <div class="leaderboard-filter">
                            <select id="school-filter" onchange="filterLeaderboard()">
                                <option value="">All Schools</option>
                                <?php foreach ($schools as $school): ?>
                                    <option value="<?= htmlspecialchars($school['school_name']) ?>">
                                        <?= htmlspecialchars($school['school_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="leaderboard-body">
                        <table class="leaderboard-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Student</th>
                                    <th>School</th>
                                    <th>Score</th>
                                    <th>Percentage</th>
                                    <th>Test Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topStudents)): ?>
                                    <?php foreach ($topStudents as $student): ?>
                                        <tr class="leaderboard-row" data-school="<?= htmlspecialchars($student['school_name'] ?? '') ?>">
                                            <td class="rank-cell">
                                                <?php if ($student['rank'] <= 3): ?>
                                                    <div class="medal rank-<?= $student['rank'] ?>">
                                                        <?php if ($student['rank'] == 1): ?>
                                                            <i class="fas fa-crown"></i>
                                                        <?php elseif ($student['rank'] == 2): ?>
                                                            <i class="fas fa-medal"></i>
                                                        <?php else: ?>
                                                            <i class="fas fa-award"></i>
                                                        <?php endif; ?>
                                                        <?= $student['rank'] ?>
                                                    </div>
                                                <?php else: ?>
                                                    <?= $student['rank'] ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($student['student_name']) ?></td>
                                            <td><?= htmlspecialchars($student['school_name'] ?? 'N/A') ?></td>
                                            <td class="score-cell"><?= htmlspecialchars($student['total_score']) ?></td>
                                            <td>
                                                <div class="progress-bar">
                                                    <div class="progress" style="width: <?= min(100, $student['percentage_score']) ?>%"></div>
                                                    <span class="progress-text"><?= number_format($student['percentage_score'], 1) ?>%</span>
                                                </div>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($student['test_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="no-data">No students data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- School Performance Section -->
            <section class="school-performance-section">
                <div class="school-performance-container">
                    <div class="school-performance-header">
                        <h2><i class="fas fa-school"></i> School Performance Rankings</h2>
                        <div class="school-performance-filter">
                            <select id="min-students-filter" onchange="filterSchoolPerformance()">
                                <option value="2">Min 2 Students</option>
                                <option value="5">Min 5 Students</option>
                                <option value="10">Min 10 Students</option>
                                <option value="20">Min 20 Students</option>
                            </select>
                        </div>
                    </div>
                    <div class="school-performance-body">
                        <table class="school-performance-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>School</th>
                                    <th>Students</th>
                                    <th>Avg. Score</th>
                                    <th>Max Score</th>
                                    <th>Avg. GWA</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($schoolPerformance)): ?>
                                    <?php foreach ($schoolPerformance as $school): ?>
                                        <tr class="school-row" data-students="<?= $school['total_students'] ?>">
                                            <td class="rank-cell">
                                                <?php if ($school['rank'] <= 3): ?>
                                                    <div class="medal rank-<?= $school['rank'] ?>">
                                                        <?php if ($school['rank'] == 1): ?>
                                                            <i class="fas fa-trophy"></i>
                                                        <?php elseif ($school['rank'] == 2): ?>
                                                            <i class="fas fa-medal"></i>
                                                        <?php else: ?>
                                                            <i class="fas fa-award"></i>
                                                        <?php endif; ?>
                                                        <?= $school['rank'] ?>
                                                    </div>
                                                <?php else: ?>
                                                    <?= $school['rank'] ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($school['school_name']) ?></td>
                                            <td><?= $school['total_students'] ?></td>
                                            <td><?= number_format($school['avg_score'], 1) ?></td>
                                            <td><?= $school['max_score'] ?></td>
                                            <td><?= number_format($school['avg_gwa'], 2) ?></td>
                                            <td>
                                                <div class="star-rating" data-rating="<?= min(5, max(1, round($school['avg_score'] / 20))) ?>">
                                                    <span class="star" data-value="1"><i class="fas fa-star"></i></span>
                                                    <span class="star" data-value="2"><i class="fas fa-star"></i></span>
                                                    <span class="star" data-value="3"><i class="fas fa-star"></i></span>
                                                    <span class="star" data-value="4"><i class="fas fa-star"></i></span>
                                                    <span class="star" data-value="5"><i class="fas fa-star"></i></span>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="no-data">No school performance data available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Strand Performance Section -->
            <section class="strand-performance-section">
                <div class="strand-performance-container">
                    <div class="strand-performance-header">
                        <h2><i class="fas fa-book"></i> Strand Performance Analysis</h2>
                    </div>
                    <div class="strand-performance-body">
                        <div class="strand-chart-container">
                            <canvas id="strand-chart"></canvas>
                        </div>
                        <div class="strand-table-container">
                            <table class="strand-performance-table">
                                <thead>
                                    <tr>
                                        <th>Strand</th>
                                        <th>Students</th>
                                        <th>Avg. Score</th>
                                        <th>Max Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($strandPerformance)): ?>
                                        <?php foreach ($strandPerformance as $strand): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($strand['strand_name']) ?></td>
                                                <td><?= $strand['total_students'] ?></td>
                                                <td><?= number_format($strand['avg_score'], 1) ?></td>
                                                <td><?= $strand['max_score'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="no-data">No strand performance data available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Recent Exams Section -->
            <section class="recent-exams-section">
                <div class="recent-exams-container">
                    <div class="recent-exams-header">
                        <h2><i class="fas fa-history"></i> Recent Exams</h2>
                        <a href="/admin/reports/all_test_results.php" class="view-all-btn">View All</a>
                    </div>
                    <div class="recent-exams-body">
                        <table class="recent-exams-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Student</th>
                                    <th>School</th>
                                    <th>Score</th>
                                    <th>Percentage</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentExams)): ?>
                                    <?php foreach ($recentExams as $exam): ?>
                                        <tr>
                                            <td><?= $exam['attempt_id'] ?></td>
                                            <td><?= htmlspecialchars($exam['student_name']) ?></td>
                                            <td><?= htmlspecialchars($exam['school_name'] ?? 'N/A') ?></td>
                                            <td><?= $exam['total_score'] ?></td>
                                            <td>
                                                <div class="mini-progress">
                                                    <div class="mini-progress-bar" style="width: <?= min(100, $exam['percentage_score']) ?>%"></div>
                                                    <span><?= number_format($exam['percentage_score'], 1) ?>%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?= strtolower($exam['status']) ?>">
                                                    <?= $exam['status'] ?>
                                                </span>
                                            </td>
                                            <td><?= date('M d, Y', strtotime($exam['created_at'])) ?></td>
                                            <td>
                                                <a href="/admin/reports/student_results.php?student_id=<?= $exam['attempt_id'] ?>" class="action-btn">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="no-data">No recent exams available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Footer -->
            <footer class="main-footer">
                <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($institutionName) ?> <?= htmlspecialchars($campusName) ?> | <?= htmlspecialchars($departmentName) ?></p>
            </footer>
        </main>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script>
    
    <!-- Page Scripts -->
    <script>
        // Data from PHP for charts
        const testAttemptTrends = <?= json_encode($testAttemptTrends) ?>;
        const subjectPerformance = <?= json_encode($subjectPerformance) ?>;
        const strandPerformance = <?= json_encode($strandPerformance) ?>;

        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Only initialize charts if data is available
            if (testAttemptTrends && testAttemptTrends.length > 0) {
                initAttemptsChart();
            }
            
            if (subjectPerformance && subjectPerformance.length > 0) {
                initSubjectsChart();
            }
            
            if (strandPerformance && strandPerformance.length > 0) {
                initStrandChart();
            }
            
            initStarRatings();
            
            // Initialize filters
            filterLeaderboard();
            filterSchoolPerformance();
        });

        // Attempts Chart
        function initAttemptsChart() {
            const ctx = document.getElementById('attempts-chart').getContext('2d');
            
            // Prepare data
            const labels = testAttemptTrends.map(day => day.test_date);
            const completedData = testAttemptTrends.map(day => day.completed_attempts);
            const expiredData = testAttemptTrends.map(day => day.expired_attempts);
            const totalData = testAttemptTrends.map(day => day.total_attempts);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Attempts',
                            data: totalData,
                            borderColor: '#006400',
                            backgroundColor: '#00640020',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.2
                        },
                        {
                            label: 'Completed',
                            data: completedData,
                            borderColor: '#22C55E',
                            backgroundColor: '#22C55E20',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.2
                        },
                        {
                            label: 'Expired',
                            data: expiredData,
                            borderColor: '#F97316',
                            backgroundColor: '#F9731620',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Exam Attempts by Day',
                            font: {
                                size: 16
                            }
                        },
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
        }

        // Subjects Performance Chart
        function initSubjectsChart() {
            const ctx = document.getElementById('subjects-chart').getContext('2d');
            
            // Prepare data
            const labels = subjectPerformance.map(subject => subject.subject_name);
            const successRates = subjectPerformance.map(subject => subject.success_rate);
            const totalStudents = subjectPerformance.map(subject => subject.total_attempts);
            
            // Generate background colors based on success rate
            const backgroundColors = successRates.map(rate => {
                if (rate >= 80) return '#00640080'; // Good - Green (ISU Color)
                if (rate >= 60) return '#3B82F680'; // Medium - Blue
                if (rate >= 40) return '#F9731680'; // Low - Orange
                return '#EF444480'; // Poor - Red
            });
            
            const borderColors = backgroundColors.map(color => color.replace('80', ''));
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Success Rate (%)',
                            data: successRates,
                            backgroundColor: backgroundColors,
                            borderColor: borderColors,
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Success Rate'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Subject'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Subject Performance Analysis',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const dataIndex = context.dataIndex;
                                    return [
                                        'Success Rate: ' + successRates[dataIndex].toFixed(1) + '%',
                                        'Students: ' + totalStudents[dataIndex]
                                    ];
                                }
                            }
                        },
                        datalabels: {
                            display: true,
                            color: '#333',
                            font: {
                                weight: 'bold'
                            },
                            formatter: function(value) {
                                return value.toFixed(1) + '%';
                            },
                            anchor: 'end',
                            align: 'top'
                        }
                    }
                }
            });
        }

        // Strand Performance Chart
        function initStrandChart() {
            if (!document.getElementById('strand-chart')) return;
            
            const ctx = document.getElementById('strand-chart').getContext('2d');
            
            // Skip if no data
            if (!strandPerformance || strandPerformance.length === 0) {
                return;
            }
            
            // Prepare data
            const labels = strandPerformance.map(strand => strand.strand_name);
            const avgScores = strandPerformance.map(strand => strand.avg_score);
            const studentCounts = strandPerformance.map(strand => strand.total_students);
            
            // Generate gradient colors
            const gradientColors = labels.map((_, i) => {
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                // ISU Colors - Green and Gold
                gradient.addColorStop(0, i % 2 === 0 ? `rgba(0, 100, 0, 0.8)` : `rgba(255, 215, 0, 0.8)`);
                gradient.addColorStop(1, i % 2 === 0 ? `rgba(0, 100, 0, 0.2)` : `rgba(255, 215, 0, 0.2)`);
                return gradient;
            });
            
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Average Score',
                            data: avgScores,
                            backgroundColor: 'rgba(0, 100, 0, 0.2)',
                            borderColor: 'rgb(0, 100, 0)',
                            pointBackgroundColor: 'rgb(0, 100, 0)',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgb(0, 100, 0)',
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            beginAtZero: true,
                            suggestedMax: Math.max(...avgScores) * 1.1
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Average Score by Strand',
                            font: {
                                size: 16
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const dataIndex = context.dataIndex;
                                    return [
                                        'Avg. Score: ' + avgScores[dataIndex].toFixed(1),
                                        'Students: ' + studentCounts[dataIndex]
                                    ];
                                }
                            }
                        }
                    }
                }
            });
        }

        // Initialize Star Ratings
        function initStarRatings() {
            const ratingElements = document.querySelectorAll('.star-rating');
            
            ratingElements.forEach(ratingElement => {
                const rating = parseInt(ratingElement.dataset.rating);
                const stars = ratingElement.querySelectorAll('.star');
                
                stars.forEach(star => {
                    const value = parseInt(star.dataset.value);
                    if (value <= rating) {
                        star.classList.add('active');
                    }
                });
            });
        }

        // Filter leaderboard by school
        function filterLeaderboard() {
            const schoolFilter = document.getElementById('school-filter').value;
            const rows = document.querySelectorAll('.leaderboard-row');
            
            rows.forEach(row => {
                const school = row.getAttribute('data-school');
                if (!schoolFilter || school === schoolFilter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Filter school performance by minimum students
        function filterSchoolPerformance() {
            const minStudents = parseInt(document.getElementById('min-students-filter').value);
            const rows = document.querySelectorAll('.school-row');
            
            rows.forEach(row => {
                const students = parseInt(row.getAttribute('data-students'));
                if (students >= minStudents) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

                    // Refresh chart
        function refreshChart(chartId) {
            // In a real application, this would fetch new data from the server
            // For now, we'll just reload the page
            location.reload();
        }
    </script>
</body>
</html>