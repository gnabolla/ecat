<?php
// Student profile completion form (student/complete_profile.php)
require_once __DIR__ . '/../bootstrap.php';

// Require login
requireStudentLogin();

// Get student information
$studentId = $_SESSION['student_id'];
$statement = $db->query(
    "SELECT * FROM students WHERE student_id = ?",
    [$studentId]
);
$student = $statement->fetch();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Required fields
    $requiredFields = [
        'first_name'          => 'First Name',
        'last_name'           => 'Last Name',
        'email'               => 'Email',
        'contact_number'      => 'Contact Number',
        'sex'                 => 'Sex',
        'birthday'            => 'Birthday',
        'lrn'                 => 'LRN',
        'gwa'                 => 'GWA',
        'school_id'           => 'School',
        'strand_id'           => 'Strand',
        'first_preference_id' => 'First Choice Program',
        'province_id'         => 'Province',
        'municipality_id'     => 'Municipality',
        'barangay_id'         => 'Barangay'
    ];
    
    // Validate required fields
    foreach ($requiredFields as $field => $label) {
        if (empty($_POST[$field])) {
            $errors[] = "$label is required.";
        }
    }
    
    // Validate email
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    
    // Validate GWA (decimal 70–100)
    if (!empty($_POST['gwa'])) {
        $gwa = floatval($_POST['gwa']);
        if ($gwa < 70 || $gwa > 100) {
            $errors[] = "GWA must be between 70 and 100.";
        }
    }
    
    // If no errors, update the student record
    if (empty($errors)) {
        try {
            $db->query(
                "UPDATE students
                SET first_name = ?, 
                    last_name = ?, 
                    middle_name = ?, 
                    email = ?, 
                    contact_number = ?, 
                    sex = ?, 
                    birthday = ?, 
                    lrn = ?, 
                    gwa = ?, 
                    school_id = ?, 
                    strand_id = ?, 
                    first_preference_id = ?, 
                    second_preference_id = ?,
                    enrollment_status = ?,
                    province_id = ?,
                    municipality_id = ?,
                    barangay_id = ?,
                    purok = ?
                WHERE student_id = ?",
                [
                    $_POST['first_name'],
                    $_POST['last_name'],
                    $_POST['middle_name'] ?? null,
                    $_POST['email'],
                    $_POST['contact_number'],
                    $_POST['sex'],
                    $_POST['birthday'],
                    $_POST['lrn'],
                    $_POST['gwa'],
                    $_POST['school_id'],
                    $_POST['strand_id'],
                    $_POST['first_preference_id'],
                    $_POST['second_preference_id'] ?? null,
                    $_POST['enrollment_status'] ?? null,
                    $_POST['province_id'],
                    $_POST['municipality_id'],
                    $_POST['barangay_id'],
                    $_POST['purok'] ?? null,
                    $studentId
                ]
            );
            
            // Update session name
            $_SESSION['student_name'] = $_POST['first_name'] . ' ' . $_POST['last_name'];
            
            // Redirect
            redirect('/student/dashboard.php', 'Profile updated successfully!', 'success');
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

// Load all data for dropdowns
// Get all schools for dropdown
$schoolsStatement = $db->query("SELECT id, school_name FROM schools ORDER BY school_name");
$schools = $schoolsStatement->fetchAll();

// Get all strands for dropdown
$strandsStatement = $db->query("SELECT strand_id, name FROM strands ORDER BY name");
$strands = $strandsStatement->fetchAll();

// Get all courses for dropdown
$coursesStatement = $db->query(
    "SELECT c.course_id, c.course_name, cam.campus_name 
     FROM courses c
     JOIN campuses cam ON c.campus_id = cam.campus_id
     ORDER BY cam.campus_name, c.course_name"
);
$courses = $coursesStatement->fetchAll();

// Get all provinces
$provincesStatement = $db->query("SELECT province_id, name FROM provinces ORDER BY name");
$provinces = $provincesStatement->fetchAll();

// Get all municipalities
$municipalitiesStatement = $db->query(
    "SELECT municipality_id, name, province_id 
     FROM municipalities 
     ORDER BY name"
);
$municipalities = $municipalitiesStatement->fetchAll();

// Get all barangays
$barangaysStatement = $db->query(
    "SELECT barangay_id, name, municipality_id 
     FROM barangays 
     ORDER BY name"
);
$barangays = $barangaysStatement->fetchAll();

// Group courses by campus
$campusCourses = [];
foreach ($courses as $course) {
    if (!isset($campusCourses[$course['campus_name']])) {
        $campusCourses[$course['campus_name']] = [];
    }
    $campusCourses[$course['campus_name']][] = $course;
}

$title = "Complete Your Profile - ECAT System";
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
            max-width: 800px; 
            margin: 0 auto; 
            padding: 20px;
        }
        header {
            background-color: #4CAF50; 
            color: white;
            padding: 15px 20px; 
            display: flex;
            justify-content: space-between; 
            align-items: center;
            margin-bottom: 30px;
        }
        .page-title { 
            margin: 0; 
        }
        .back-link a {
            color: white; 
            text-decoration: none;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 8px 15px; 
            border-radius: 4px;
        }
        .back-link a:hover {
            background-color: rgba(0, 0, 0, 0.3);
        }
        .profile-form {
            background-color: white; 
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
            padding: 20px;
        }
        h2 { 
            color: #333; 
            margin-top: 0; 
            border-bottom: 1px solid #eee; 
            padding-bottom: 10px; 
        }
        .form-section { 
            margin-bottom: 20px; 
        }
        .form-section h3 { 
            color: #4CAF50; 
            margin-top: 0; 
        }
        .form-grid {
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 15px;
        }
        .form-group { 
            margin-bottom: 15px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
        }
        .required::after { 
            content: " *"; 
            color: #f44336; 
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%; 
            padding: 10px; 
            border: 1px solid #ddd;
            border-radius: 4px; 
            box-sizing: border-box; 
            font-size: 16px;
        }
        .button {
            background-color: #4CAF50; 
            color: white; 
            border: none;
            padding: 12px 20px; 
            border-radius: 4px; 
            cursor: pointer;
            font-size: 16px; 
            display: inline-block; 
            text-decoration: none;
        }
        .button:hover { 
            background-color: #45a049; 
        }
        .error-list {
            background-color: #ffebee; 
            color: #c62828;
            border: 1px solid #ffcdd2; 
            padding: 10px;
            border-radius: 4px; 
            margin-bottom: 20px;
        }
        .error-list ul { 
            margin: 0; 
            padding-left: 20px; 
        }
        .hints { 
            font-size: 14px; 
            color: #666; 
            margin-top: 5px; 
        }
        .alert-box {
            background-color: #e8f5e9; 
            color: #2e7d32; 
            border: 1px solid #c8e6c9; 
            padding: 10px; 
            margin-bottom: 20px; 
            border-radius: 4px;
        }
    </style>
</head>
<body>
<header>
    <h1 class="page-title">Complete Your Profile</h1>
    <div class="back-link">
        <a href="/student/dashboard.php">Back to Dashboard</a>
    </div>
</header>

<div class="container">
    <?php if (!empty($errors)): ?>
        <div class="error-list">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="profile-form">
        <h2>Student Information</h2>
        <p>Please complete all required fields (*) to enable taking the ECAT exam.</p>
        
        <form action="/student/complete_profile.php" method="post">
            <!-- Personal Information -->
            <div class="form-section">
                <h3>Personal Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="first_name" class="required">First Name</label>
                        <input type="text" id="first_name" name="first_name"
                               value="<?= htmlspecialchars($student['first_name'] ?? '') ?>"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="required">Last Name</label>
                        <input type="text" id="last_name" name="last_name"
                               value="<?= htmlspecialchars($student['last_name'] ?? '') ?>"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name"
                               value="<?= htmlspecialchars($student['middle_name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="sex" class="required">Sex</label>
                        <select id="sex" name="sex" required>
                            <option value="">-- Select --</option>
                            <option value="Male" <?= ($student['sex'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($student['sex'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="birthday" class="required">Birthday</label>
                        <input type="date" id="birthday" name="birthday"
                               value="<?= htmlspecialchars($student['birthday'] ?? '') ?>"
                               required>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="form-section">
                <h3>Contact Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="email" class="required">Email Address</label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($student['email'] ?? '') ?>"
                               required>
                    </div>
                    <div class="form-group">
                        <label for="contact_number" class="required">Contact Number</label>
                        <input type="tel" id="contact_number" name="contact_number"
                               value="<?= htmlspecialchars($student['contact_number'] ?? '') ?>"
                               required>
                        <div class="hints">Include country code (e.g., +63)</div>
                    </div>
                </div>
            </div>
            
            <!-- Address Information -->
            <div class="form-section">
                <h3>Address Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="province_id" class="required">Province</label>
                        <select id="province_id" name="province_id" required>
                            <option value="">-- Select Province --</option>
                            <?php foreach ($provinces as $province): ?>
                                <option value="<?= $province['province_id'] ?>"
                                    <?= ($student['province_id'] ?? '') == $province['province_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($province['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="municipality_id" class="required">City/Municipality</label>
                        <select id="municipality_id" name="municipality_id" required>
                            <option value="">-- Select City/Municipality --</option>
                            <?php foreach ($municipalities as $municipality): ?>
                                <option value="<?= $municipality['municipality_id'] ?>" 
                                       data-province="<?= $municipality['province_id'] ?>"
                                       <?= ($student['municipality_id'] ?? '') == $municipality['municipality_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($municipality['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="barangay_id" class="required">Barangay</label>
                        <select id="barangay_id" name="barangay_id" required>
                            <option value="">-- Select Barangay --</option>
                            <?php foreach ($barangays as $barangay): ?>
                                <option value="<?= $barangay['barangay_id'] ?>" 
                                       data-municipality="<?= $barangay['municipality_id'] ?>"
                                       <?= ($student['barangay_id'] ?? '') == $barangay['barangay_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($barangay['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="purok">Purok/Street/House No.</label>
                        <input type="text" id="purok" name="purok"
                               value="<?= htmlspecialchars($student['purok'] ?? '') ?>">
                        <div class="hints">Enter your specific address details</div>
                    </div>
                </div>
            </div>
            
            <!-- Academic Information -->
            <div class="form-section">
                <h3>Academic Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="lrn" class="required">Learner Reference Number (LRN)</label>
                        <input type="text" id="lrn" name="lrn"
                               value="<?= htmlspecialchars($student['lrn'] ?? '') ?>"
                               maxlength="20" required>
                    </div>
                    <div class="form-group">
                        <label for="gwa" class="required">Grade-Weighted Average (GWA)</label>
                        <input type="number" id="gwa" name="gwa"
                               value="<?= htmlspecialchars($student['gwa'] ?? '') ?>"
                               min="70" max="100" step="0.01" required>
                        <div class="hints">Enter a value between 70 and 100</div>
                    </div>
                    <div class="form-group">
                        <label for="school_id" class="required">Previous School</label>
                        <select id="school_id" name="school_id" required>
                            <option value="">-- Select School --</option>
                            <?php foreach ($schools as $school): ?>
                                <option value="<?= $school['id'] ?>"
                                    <?= ($student['school_id'] ?? '') == $school['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($school['school_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="strand_id" class="required">SHS Strand</label>
                        <select id="strand_id" name="strand_id" required>
                            <option value="">-- Select Strand --</option>
                            <?php foreach ($strands as $strand): ?>
                                <option value="<?= $strand['strand_id'] ?>"
                                    <?= ($student['strand_id'] ?? '') == $strand['strand_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($strand['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="enrollment_status">Enrollment Status</label>
                        <select id="enrollment_status" name="enrollment_status">
                            <option value="">-- Select Status --</option>
                            <option value="Freshman" <?= ($student['enrollment_status'] ?? '') === 'Freshman' ? 'selected' : '' ?>>Freshman</option>
                            <option value="Transferee" <?= ($student['enrollment_status'] ?? '') === 'Transferee' ? 'selected' : '' ?>>Transferee</option>
                            <option value="Second Course" <?= ($student['enrollment_status'] ?? '') === 'Second Course' ? 'selected' : '' ?>>Second Course</option>
                            <option value="Others" <?= ($student['enrollment_status'] ?? '') === 'Others' ? 'selected' : '' ?>>Others</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Program Preferences -->
            <div class="form-section">
                <h3>Program Preferences</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="first_preference_id" class="required">First Choice Program</label>
                        <select id="first_preference_id" name="first_preference_id" required>
                            <option value="">-- Select Program --</option>
                            <?php foreach ($campusCourses as $campusName => $campusPrograms): ?>
                                <optgroup label="<?= htmlspecialchars($campusName) ?>">
                                    <?php foreach ($campusPrograms as $program): ?>
                                        <option value="<?= $program['course_id'] ?>"
                                            <?= ($student['first_preference_id'] ?? '') == $program['course_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($program['course_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="second_preference_id">Second Choice Program</label>
                        <select id="second_preference_id" name="second_preference_id">
                            <option value="">-- Select Program --</option>
                            <?php foreach ($campusCourses as $campusName => $campusPrograms): ?>
                                <optgroup label="<?= htmlspecialchars($campusName) ?>">
                                    <?php foreach ($campusPrograms as $program): ?>
                                        <option value="<?= $program['course_id'] ?>"
                                            <?= ($student['second_preference_id'] ?? '') == $program['course_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($program['course_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Submit -->
            <div class="form-section">
                <button type="submit" class="button">Save Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get dropdown elements
    const provinceSelect = document.getElementById('province_id');
    const municipalitySelect = document.getElementById('municipality_id');
    const barangaySelect = document.getElementById('barangay_id');
    
    // Function to filter municipalities based on province
    function filterMunicipalities() {
        const provinceId = provinceSelect.value;
        
        // Hide all municipality options first
        Array.from(municipalitySelect.options).forEach(option => {
            if (option.value === '') {
                // Keep the placeholder option visible
                option.style.display = '';
            } else {
                const optionProvinceId = option.getAttribute('data-province');
                option.style.display = (optionProvinceId === provinceId) ? '' : 'none';
            }
        });
        
        // Reset municipality selection
        municipalitySelect.value = '';
        
        // Also reset and hide all barangays since municipality changed
        barangaySelect.value = '';
        Array.from(barangaySelect.options).forEach(option => {
            option.style.display = option.value === '' ? '' : 'none';
        });
    }
    
    // Function to filter barangays based on municipality
    function filterBarangays() {
        const municipalityId = municipalitySelect.value;
        
        // Hide all barangay options first
        Array.from(barangaySelect.options).forEach(option => {
            if (option.value === '') {
                // Keep the placeholder option visible
                option.style.display = '';
            } else {
                const optionMunicipalityId = option.getAttribute('data-municipality');
                option.style.display = (optionMunicipalityId === municipalityId) ? '' : 'none';
            }
        });
        
        // Reset barangay selection
        barangaySelect.value = '';
    }
    
    // Add event listeners
    provinceSelect.addEventListener('change', filterMunicipalities);
    municipalitySelect.addEventListener('change', filterBarangays);
    
    // Initial filtering on page load
    if (provinceSelect.value) {
        filterMunicipalities();
        
        if (municipalitySelect.value) {
            filterBarangays();
        }
    }
});
</script>
</body>
</html>