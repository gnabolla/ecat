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

// Get all schools for dropdown
$schoolsStatement = $db->query("SELECT id, school_name FROM schools ORDER BY school_name");
$schools = $schoolsStatement->fetchAll();

// Get all strands for dropdown
$strandsStatement = $db->query("SELECT strand_id, name FROM strands ORDER BY name");
$strands = $strandsStatement->fetchAll();

// Get all courses for dropdown
$coursesStatement = $db->query(
    "SELECT c.course_id, c.course_name, cam.campus_name, cam.campus_id
     FROM courses c
     JOIN campuses cam ON c.campus_id = cam.campus_id
     ORDER BY cam.campus_name, c.course_name"
);
$courses = $coursesStatement->fetchAll();

// Get all provinces for dropdown
$provincesStatement = $db->query("SELECT province_id, name FROM provinces ORDER BY name");
$provinces = $provincesStatement->fetchAll();

// Get all municipalities for dropdown
$municipalitiesStatement = $db->query(
    "SELECT m.municipality_id, m.name, p.name AS province_name, m.province_id
     FROM municipalities m
     JOIN provinces p ON m.province_id = p.province_id
     ORDER BY p.name, m.name"
);
$municipalities = $municipalitiesStatement->fetchAll();

// Get all barangays for dropdown
$barangaysStatement = $db->query(
    "SELECT b.barangay_id, b.name, m.name AS municipality_name, m.municipality_id
     FROM barangays b
     JOIN municipalities m ON b.municipality_id = m.municipality_id
     ORDER BY m.name, b.name"
);
$barangays = $barangaysStatement->fetchAll();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $errors = [];
    
    // Required fields validation
    $requiredFields = [
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'email' => 'Email',
        'contact_number' => 'Contact Number',
        'sex' => 'Sex',
        'birthday' => 'Birthday',
        'lrn' => 'LRN',
        'gwa' => 'GWA',
        'school_id' => 'School',
        'strand_id' => 'Strand',
        'first_preference_id' => 'First Choice Program',
        'province_id' => 'Province',
        'municipality_id' => 'Municipality',
        'barangay_id' => 'Barangay'
    ];
    
    foreach ($requiredFields as $field => $label) {
        if (empty($_POST[$field])) {
            $errors[] = "$label is required.";
        }
    }
    
    // Email validation
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    
    // GWA validation (should be a decimal between 70 and 100)
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
                "UPDATE students SET 
                    first_name = ?, 
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
            
            // Update student name in session
            $_SESSION['student_name'] = $_POST['first_name'] . ' ' . $_POST['last_name'];
            
            // Redirect to dashboard with success message
            redirect('/student/dashboard.php', 'Profile updated successfully!', 'success');
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}

$title = "Complete Your Profile - ECAT System";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Include the Choices.js library for searchable select boxes -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
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
        
        .required:after {
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
        
        /* Choices.js custom styling */
        .choices {
            margin-bottom: 0;
        }
        
        .choices__inner {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-height: 40px;
            padding: 7px 7.5px;
        }
        
        .choices__input {
            background-color: transparent;
        }
        
        .choices__list--dropdown {
            z-index: 10;
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
            <div class="alert-box" style="background-color: #e8f5e9; color: #2e7d32; border-color: #c8e6c9;">
                <p><strong>Tip:</strong> You can search for schools, strands, and programs by typing in the dropdown boxes. Click on a dropdown and start typing to find what you're looking for quickly.</p>
            </div>
            
            <form action="/student/complete_profile.php" method="post">
                <div class="form-section">
                    <h3>Personal Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="first_name" class="required">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($student['first_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="last_name" class="required">Last Name</label>
                            <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($student['last_name'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" value="<?= htmlspecialchars($student['middle_name'] ?? '') ?>">
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
                            <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($student['birthday'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Contact Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="email" class="required">Email Address</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email'] ?? '') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_number" class="required">Contact Number</label>
                            <input type="tel" id="contact_number" name="contact_number" value="<?= htmlspecialchars($student['contact_number'] ?? '') ?>" required>
                            <div class="hints">Include country code (e.g., +63)</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Address Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="province_id" class="required">Province</label>
                            <select id="province_id" name="province_id" required>
                                <option value="">-- Select Province --</option>
                                <?php foreach ($provinces as $province): ?>
                                    <option value="<?= $province['province_id'] ?>" <?= ($student['province_id'] ?? '') == $province['province_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($province['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="municipality_id" class="required">City/Municipality</label>
                            <select id="municipality_id" name="municipality_id" required>
                                <option value="">-- Select City/Municipality --</option>
                                <?php 
                                // Group municipalities by province for better organization
                                $municipalitiesByProvince = [];
                                foreach ($municipalities as $municipality) {
                                    if (!isset($municipalitiesByProvince[$municipality['province_name']])) {
                                        $municipalitiesByProvince[$municipality['province_name']] = [];
                                    }
                                    $municipalitiesByProvince[$municipality['province_name']][] = $municipality;
                                }
                                
                                // Display municipalities grouped by province
                                foreach ($municipalitiesByProvince as $provinceName => $provinceMunicipalities): 
                                ?>
                                    <optgroup label="<?= htmlspecialchars($provinceName) ?>">
                                        <?php foreach ($provinceMunicipalities as $municipality): ?>
                                            <option value="<?= $municipality['municipality_id'] ?>" 
                                                    data-province="<?= $municipality['province_id'] ?>"
                                                    <?= ($student['municipality_id'] ?? '') == $municipality['municipality_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($municipality['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="barangay_id" class="required">Barangay</label>
                            <select id="barangay_id" name="barangay_id" required>
                                <option value="">-- Select Barangay --</option>
                                <?php 
                                // Group barangays by municipality for better organization
                                $barangaysByMunicipality = [];
                                foreach ($barangays as $barangay) {
                                    if (!isset($barangaysByMunicipality[$barangay['municipality_name']])) {
                                        $barangaysByMunicipality[$barangay['municipality_name']] = [];
                                    }
                                    $barangaysByMunicipality[$barangay['municipality_name']][] = $barangay;
                                }
                                
                                // Display barangays grouped by municipality
                                foreach ($barangaysByMunicipality as $municipalityName => $municipalityBarangays): 
                                ?>
                                    <optgroup label="<?= htmlspecialchars($municipalityName) ?>">
                                        <?php foreach ($municipalityBarangays as $barangay): ?>
                                            <option value="<?= $barangay['barangay_id'] ?>" 
                                                    data-municipality="<?= $barangay['municipality_id'] ?>"
                                                    <?= ($student['barangay_id'] ?? '') == $barangay['barangay_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($barangay['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="purok">Purok/Street/House No.</label>
                            <input type="text" id="purok" name="purok" value="<?= htmlspecialchars($student['purok'] ?? '') ?>">
                            <div class="hints">Enter your specific address details</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Academic Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="lrn" class="required">Learner Reference Number (LRN)</label>
                            <input type="text" id="lrn" name="lrn" value="<?= htmlspecialchars($student['lrn'] ?? '') ?>" maxlength="20" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="gwa" class="required">Grade-Weighted Average (GWA)</label>
                            <input type="number" id="gwa" name="gwa" value="<?= htmlspecialchars($student['gwa'] ?? '') ?>" min="70" max="100" step="0.01" required>
                            <div class="hints">Enter a value between 70 and 100</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="school_id" class="required">Previous School</label>
                            <select id="school_id" name="school_id" required>
                                <option value="">-- Select School --</option>
                                <?php foreach ($schools as $school): ?>
                                    <option value="<?= $school['id'] ?>" <?= ($student['school_id'] ?? '') == $school['id'] ? 'selected' : '' ?>>
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
                                    <option value="<?= $strand['strand_id'] ?>" <?= ($student['strand_id'] ?? '') == $strand['strand_id'] ? 'selected' : '' ?>>
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
                
                <div class="form-section">
                    <h3>Program Preferences</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="first_preference_id" class="required">First Choice Program</label>
                            <select id="first_preference_id" name="first_preference_id" required>
                                <option value="">-- Select Program --</option>
                                <?php 
                                // Group courses by campus for better organization
                                $coursesByGroup = [];
                                foreach ($courses as $course) {
                                    if (!isset($coursesByGroup[$course['campus_name']])) {
                                        $coursesByGroup[$course['campus_name']] = [];
                                    }
                                    $coursesByGroup[$course['campus_name']][] = $course;
                                }
                                
                                // Display courses grouped by campus
                                foreach ($coursesByGroup as $campusName => $campusCourses): 
                                ?>
                                    <optgroup label="<?= htmlspecialchars($campusName) ?>">
                                        <?php foreach ($campusCourses as $course): ?>
                                            <option value="<?= $course['course_id'] ?>" <?= ($student['first_preference_id'] ?? '') == $course['course_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($course['course_name']) ?>
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
                                <?php 
                                // Display courses grouped by campus
                                foreach ($coursesByGroup as $campusName => $campusCourses): 
                                ?>
                                    <optgroup label="<?= htmlspecialchars($campusName) ?>">
                                        <?php foreach ($campusCourses as $course): ?>
                                            <option value="<?= $course['course_id'] ?>" <?= ($student['second_preference_id'] ?? '') == $course['course_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($course['course_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <button type="submit" class="button">Save Profile</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Initialize searchable select boxes with Choices.js
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize school dropdown with search
            const schoolSelect = document.getElementById('school_id');
            const schoolChoices = new Choices(schoolSelect, {
                searchEnabled: true,
                searchEnabled: true,
                searchPlaceholderValue: 'Search for a municipality...',
                placeholder: true,
                placeholderValue: '-- Select Municipality --',
                itemSelectText: 'Select',
                position: 'auto',
                noResultsText: 'No municipalities found'
            });
            
            const barangaySelect = document.getElementById('barangay_id');
            const barangayChoices = new Choices(barangaySelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Search for a barangay...',
                placeholder: true,
                placeholderValue: '-- Select Barangay --',
                itemSelectText: 'Select',
                position: 'auto',
                noResultsText: 'No barangays found'
            });
            
            // Add linked dropdown behavior for location fields
            provinceSelect.addEventListener('choice', function(event) {
                if (!event.detail.choice) return;
                
                const selectedProvinceId = event.detail.choice.value;
                
                // Filter municipalities by province
                const municipalityOptions = Array.from(document.querySelectorAll('#municipality_id option'))
                    .filter(option => !option.value || option.dataset.province === selectedProvinceId);
                
                // Clear and rebuild municipality dropdown
                municipalityChoices.clearStore();
                
                if (municipalityOptions.length > 0) {
                    const groupedOptions = {};
                    
                    // Group options by optgroup
                    municipalityOptions.forEach(option => {
                        const optgroupLabel = option.closest('optgroup')?.label || '';
                        if (!groupedOptions[optgroupLabel]) {
                            groupedOptions[optgroupLabel] = [];
                        }
                        
                        if (option.value) {
                            groupedOptions[optgroupLabel].push({
                                value: option.value,
                                label: option.textContent.trim(),
                                customProperties: {
                                    province: option.dataset.province
                                }
                            });
                        }
                    });
                    
                    // Add empty option
                    municipalityChoices.setChoices([
                        { value: '', label: '-- Select Municipality --', selected: true }
                    ]);
                    
                    // Add grouped options
                    Object.entries(groupedOptions).forEach(([label, choices]) => {
                        if (label && choices.length > 0) {
                            municipalityChoices.setChoices(choices, 'value', 'label', true);
                        }
                    });
                }
                
                // Also reset barangay when province changes
                barangayChoices.clearStore();
                barangayChoices.setChoices([
                    { value: '', label: '-- Select Barangay --', selected: true }
                ]);
            });
            
            municipalitySelect.addEventListener('choice', function(event) {
                if (!event.detail.choice) return;
                
                const selectedMunicipalityId = event.detail.choice.value;
                
                // Filter barangays by municipality
                const barangayOptions = Array.from(document.querySelectorAll('#barangay_id option'))
                    .filter(option => !option.value || option.dataset.municipality === selectedMunicipalityId);
                
                // Clear and rebuild barangay dropdown
                barangayChoices.clearStore();
                
                if (barangayOptions.length > 0) {
                    const groupedOptions = {};
                    
                    // Group options by optgroup
                    barangayOptions.forEach(option => {
                        const optgroupLabel = option.closest('optgroup')?.label || '';
                        if (!groupedOptions[optgroupLabel]) {
                            groupedOptions[optgroupLabel] = [];
                        }
                        
                        if (option.value) {
                            groupedOptions[optgroupLabel].push({
                                value: option.value,
                                label: option.textContent.trim(),
                                customProperties: {
                                    municipality: option.dataset.municipality
                                }
                            });
                        }
                    });
                    
                    // Add empty option
                    barangayChoices.setChoices([
                        { value: '', label: '-- Select Barangay --', selected: true }
                    ]);
                    
                    // Add grouped options
                    Object.entries(groupedOptions).forEach(([label, choices]) => {
                        if (label && choices.length > 0) {
                            barangayChoices.setChoices(choices, 'value', 'label', true);
                        }
                    });
                }
            });
            
            // Set initial values for linked dropdowns
            if (provinceSelect.value) {
                // Trigger province change to filter municipalities
                const provinceEvent = new CustomEvent('choice', {
                    detail: {
                        choice: {
                            value: provinceSelect.value
                        }
                    }
                });
                provinceSelect.dispatchEvent(provinceEvent);
                
                // If municipality is also set, trigger municipality change to filter barangays
                if (municipalitySelect.value) {
                    const municipalityEvent = new CustomEvent('choice', {
                        detail: {
                            choice: {
                                value: municipalitySelect.value
                            }
                        }
                    });
                    municipalitySelect.dispatchEvent(municipalityEvent);
                }
            }
        });
    </script>eholderValue: 'Search for a school...',
                placeholder: true,
                placeholderValue: '-- Select School --',
                itemSelectText: 'Select',
                noResultsText: 'No schools found',
                searchResultLimit: 10,
                position: 'auto'
            });
            
            // Initialize strand dropdown with search
            const strandSelect = document.getElementById('strand_id');
            const strandChoices = new Choices(strandSelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Search for a strand...',
                placeholder: true,
                placeholderValue: '-- Select Strand --',
                itemSelectText: 'Select',
                position: 'auto'
            });
            
            // Initialize first preference dropdown with search
            const firstPrefSelect = document.getElementById('first_preference_id');
            const firstPrefChoices = new Choices(firstPrefSelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Search for a program...',
                placeholder: true,
                placeholderValue: '-- Select Program --',
                itemSelectText: 'Select',
                noResultsText: 'No programs found',
                searchResultLimit: 10,
                position: 'auto'
            });
            
            // Initialize second preference dropdown with search
            const secondPrefSelect = document.getElementById('second_preference_id');
            const secondPrefChoices = new Choices(secondPrefSelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Search for a program...',
                placeholder: true,
                placeholderValue: '-- Select Program (Optional) --',
                itemSelectText: 'Select',
                noResultsText: 'No programs found',
                searchResultLimit: 10,
                position: 'auto'
            });
            
            // Initialize location fields
            const provinceSelect = document.getElementById('province_id');
            const provinceChoices = new Choices(provinceSelect, {
                searchEnabled: true,
                searchPlaceholderValue: 'Search for a province...',
                placeholder: true,
                placeholderValue: '-- Select Province --',
                itemSelectText: 'Select',
                position: 'auto'
            });
            
            const municipalitySelect = document.getElementById('municipality_id');
            const municipalityChoices = new Choices(municipalitySelect, {
                searchEnabled: true,
                searchPlac
</body>
</html>