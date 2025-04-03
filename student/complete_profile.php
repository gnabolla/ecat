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
    
    // Validate required
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

$title = "Complete Your Profile - ECAT System";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    
    <!-- Choices.js CSS/JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0; padding: 0;
        }
        .container {
            max-width: 800px; margin: 0 auto; padding: 20px;
        }
        header {
            background-color: #4CAF50; color: white;
            padding: 15px 20px; display: flex;
            justify-content: space-between; align-items: center;
            margin-bottom: 30px;
        }
        .page-title { margin: 0; }
        .back-link a {
            color: white; text-decoration: none;
            background-color: rgba(0, 0, 0, 0.2);
            padding: 8px 15px; border-radius: 4px;
        }
        .back-link a:hover {
            background-color: rgba(0, 0, 0, 0.3);
        }
        .profile-form {
            background-color: white; border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); padding: 20px;
        }
        h2 { color: #333; margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .form-section { margin-bottom: 20px; }
        .form-section h3 { color: #4CAF50; margin-top: 0; }
        .form-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 15px;
        }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        .required::after { content: " *"; color: #f44336; }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%; padding: 10px; border: 1px solid #ddd;
            border-radius: 4px; box-sizing: border-box; font-size: 16px;
        }
        .button {
            background-color: #4CAF50; color: white; border: none;
            padding: 12px 20px; border-radius: 4px; cursor: pointer;
            font-size: 16px; display: inline-block; text-decoration: none;
        }
        .button:hover { background-color: #45a049; }
        .error-list {
            background-color: #ffebee; color: #c62828;
            border: 1px solid #ffcdd2; padding: 10px;
            border-radius: 4px; margin-bottom: 20px;
        }
        .error-list ul { margin: 0; padding-left: 20px; }
        .hints { font-size: 14px; color: #666; margin-top: 5px; }
        
        /* Choices.js overrides for clarity */
        .choices { margin-bottom: 0; }
        .choices__inner {
            background-color: #fff; border: 1px solid #ddd;
            border-radius: 4px; min-height: 40px;
            padding: 7px 7.5px;
        }
        .choices__list--dropdown { z-index: 10; }
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
        <div class="alert-box" style="background-color: #e8f5e9; color: #2e7d32; border-color: #c8e6c9; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
            <p><strong>Tip:</strong> You can search for schools, strands, and programs by typing in the dropdown boxes. Just click on a dropdown and start typing to find what you need!</p>
        </div>
        
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
                            <option value="Male"   <?= ($student['sex'] ?? '') === 'Male'   ? 'selected' : '' ?>>Male</option>
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
                            <?php 
                            // Group municipalities by province for better organization
                            $municipalitiesByProvince = [];
                            foreach ($municipalities as $m) {
                                if (!isset($municipalitiesByProvince[$m['province_name']])) {
                                    $municipalitiesByProvince[$m['province_name']] = [];
                                }
                                $municipalitiesByProvince[$m['province_name']][] = $m;
                            }
                            foreach ($municipalitiesByProvince as $provName => $provMuns): ?>
                                <optgroup label="<?= htmlspecialchars($provName) ?>">
                                    <?php foreach ($provMuns as $mun): ?>
                                        <option value="<?= $mun['municipality_id'] ?>"
                                            data-province="<?= $mun['province_id'] ?>"
                                            <?= ($student['municipality_id'] ?? '') == $mun['municipality_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($mun['name']) ?>
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
                            // Group barangays by municipality
                            $barangaysByMunicipality = [];
                            foreach ($barangays as $b) {
                                if (!isset($barangaysByMunicipality[$b['municipality_name']])) {
                                    $barangaysByMunicipality[$b['municipality_name']] = [];
                                }
                                $barangaysByMunicipality[$b['municipality_name']][] = $b;
                            }
                            foreach ($barangaysByMunicipality as $munName => $bList): ?>
                                <optgroup label="<?= htmlspecialchars($munName) ?>">
                                    <?php foreach ($bList as $bg): ?>
                                        <option value="<?= $bg['barangay_id'] ?>"
                                            data-municipality="<?= $bg['municipality_id'] ?>"
                                            <?= ($student['barangay_id'] ?? '') == $bg['barangay_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($bg['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
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
                            <option value="Freshman"
                                <?= ($student['enrollment_status'] ?? '') === 'Freshman' ? 'selected' : '' ?>>
                                Freshman
                            </option>
                            <option value="Transferee"
                                <?= ($student['enrollment_status'] ?? '') === 'Transferee' ? 'selected' : '' ?>>
                                Transferee
                            </option>
                            <option value="Second Course"
                                <?= ($student['enrollment_status'] ?? '') === 'Second Course' ? 'selected' : '' ?>>
                                Second Course
                            </option>
                            <option value="Others"
                                <?= ($student['enrollment_status'] ?? '') === 'Others' ? 'selected' : '' ?>>
                                Others
                            </option>
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
                            <?php
                            // Group courses by campus
                            $coursesByCampus = [];
                            foreach ($courses as $c) {
                                if (!isset($coursesByCampus[$c['campus_name']])) {
                                    $coursesByCampus[$c['campus_name']] = [];
                                }
                                $coursesByCampus[$c['campus_name']][] = $c;
                            }
                            foreach ($coursesByCampus as $campusName => $campusCourses): ?>
                                <optgroup label="<?= htmlspecialchars($campusName) ?>">
                                    <?php foreach ($campusCourses as $course): ?>
                                        <option value="<?= $course['course_id'] ?>"
                                            <?= ($student['first_preference_id'] ?? '') == $course['course_id'] ? 'selected' : '' ?>>
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
                            <?php foreach ($coursesByCampus as $campusName => $campusCourses): ?>
                                <optgroup label="<?= htmlspecialchars($campusName) ?>">
                                    <?php foreach ($campusCourses as $course): ?>
                                        <option value="<?= $course['course_id'] ?>"
                                            <?= ($student['second_preference_id'] ?? '') == $course['course_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($course['course_name']) ?>
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
    // Grab all selects we want to initialize with Choices
    const schoolSelect     = document.getElementById('school_id');
    const strandSelect     = document.getElementById('strand_id');
    const firstPrefSelect  = document.getElementById('first_preference_id');
    const secondPrefSelect = document.getElementById('second_preference_id');
    const provinceSelect   = document.getElementById('province_id');
    const municipalitySelect = document.getElementById('municipality_id');
    const barangaySelect     = document.getElementById('barangay_id');
    
    // Initialize Choices
    const schoolChoices = new Choices(schoolSelect, {
        searchEnabled: true,
        searchPlaceholderValue: 'Search for a school...'
    });
    const strandChoices = new Choices(strandSelect, {
        searchEnabled: true,
        searchPlaceholderValue: 'Search for a strand...'
    });
    const firstPrefChoices = new Choices(firstPrefSelect, {
        searchEnabled: true,
        searchPlaceholderValue: 'Search for a program...'
    });
    const secondPrefChoices = new Choices(secondPrefSelect, {
        searchEnabled: true,
        searchPlaceholderValue: 'Search for a program...'
    });
    const provinceChoices = new Choices(provinceSelect, {
        searchEnabled: true,
        searchPlaceholderValue: 'Search for a province...'
    });
    const municipalityChoices = new Choices(municipalitySelect, {
        searchEnabled: true,
        searchPlaceholderValue: 'Search for a municipality...'
    });
    const barangayChoices = new Choices(barangaySelect, {
        searchEnabled: true,
        searchPlaceholderValue: 'Search for a barangay...'
    });
    
    // Helper: rebuild a <select> with Choices based on currently visible options
    function resetChoices(choicesInstance, selectElem, placeholderText) {
        // 1) Clear out the existing store in that instance
        choicesInstance.clearStore();
        // 2) Build up from whichever <optgroup>/<option> is still visible
        const optionGroups = Array.from(selectElem.querySelectorAll('optgroup'));
        const basicOptions = Array.from(selectElem.querySelectorAll(':scope > option')); 
        // We'll also start with a blank placeholder choice
        choicesInstance.setChoices([{ value: '', label: placeholderText, selected: true, disabled: false }]);

        // If we have <optgroup> elements
        if (optionGroups.length > 0) {
            optionGroups.forEach(group => {
                // filter for visible <option>
                const groupOptions = Array.from(group.querySelectorAll('option'))
                    .filter(op => op.style.display !== 'none');
                if (groupOptions.length > 0) {
                    const groupName = group.label || 'Options';
                    // Build a group
                    const choiceGroup = groupOptions.map(op => ({
                        value: op.value,
                        label: op.textContent.trim(),
                    }));
                    choicesInstance.setChoices(choiceGroup, 'value', 'label', false, groupName);
                }
            });
        } else {
            // If no grouping, we just add basic <option> that are visible
            const visibleOptions = basicOptions.filter(op => op.style.display !== 'none');
            const structured = visibleOptions.map(op => ({
                value: op.value,
                label: op.textContent.trim()
            }));
            choicesInstance.setChoices(structured, 'value', 'label', false);
        }
    }

    // ----- LINKED DROPDOWN LOGIC -----
    provinceSelect.addEventListener('change', function() {
        const selectedProvID = this.value; 
        // Show/hide municipalities
        const munOptions = municipalitySelect.querySelectorAll('option');
        munOptions.forEach(op => {
            if (!op.value) {
                // placeholder always visible
                op.style.display = '';
            } else {
                // Compare with data-province, use == to avoid string vs number mismatch
                op.style.display = (op.getAttribute('data-province') == selectedProvID)
                    ? '' : 'none';
            }
        });
        // Reset the municipality selection to blank
        municipalitySelect.value = '';
        resetChoices(municipalityChoices, municipalitySelect, '-- Select City/Municipality --');

        // Reset barangay completely
        barangaySelect.value = '';
        barangaySelect.querySelectorAll('option').forEach(op => op.style.display = '');
        resetChoices(barangayChoices, barangaySelect, '-- Select Barangay --');
    });
    
    municipalitySelect.addEventListener('change', function() {
        const selectedMunID = this.value;
        // Show/hide barangays
        const brgyOptions = barangaySelect.querySelectorAll('option');
        brgyOptions.forEach(op => {
            if (!op.value) {
                op.style.display = '';
            } else {
                op.style.display = (op.getAttribute('data-municipality') == selectedMunID)
                    ? '' : 'none';
            }
        });
        // Reset barangay to blank
        barangaySelect.value = '';
        resetChoices(barangayChoices, barangaySelect, '-- Select Barangay --');
    });

    // Simulate a change on page load if there's a saved province/municipality
    if (provinceSelect.value) {
        provinceSelect.dispatchEvent(new Event('change'));
    }
    if (municipalitySelect.value) {
        municipalitySelect.dispatchEvent(new Event('change'));
    }
});
</script>
</body>
</html>
