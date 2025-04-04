-- Make sure to back up your 'students' table before running this!

INSERT INTO students (
    student_id, passcode, last_name, first_name, middle_name,
    first_preference_id, second_preference_id, strand_id, enrollment_status,
    school_id, lrn, gwa, barangay_id, sex, birthday, email, contact_number,
    created_at, municipality_id, province_id, purok
)
SELECT
    st.student_id, st.passcode, st.last_name, st.first_name, st.middle_name,
    st.first_preference_id, st.second_preference_id, st.strand_id, st.enrollment_status,
    st.school_id, st.lrn, st.gwa, st.barangay_id, st.sex, st.birthday, st.email, st.contact_number,
    st.created_at, st.municipality_id, st.province_id, st.purok
FROM
    students_temp st
ON DUPLICATE KEY UPDATE
    -- For names and passcode, maybe always update? Adjust if needed.
    passcode = VALUES(passcode),
    last_name = VALUES(last_name),
    first_name = VALUES(first_name),
    -- For other fields, only update if the *existing* value in 'students' is NULL
    middle_name = IF(students.middle_name IS NULL, VALUES(middle_name), students.middle_name),
    first_preference_id = IF(students.first_preference_id IS NULL, VALUES(first_preference_id), students.first_preference_id),
    second_preference_id = IF(students.second_preference_id IS NULL, VALUES(second_preference_id), students.second_preference_id),
    strand_id = IF(students.strand_id IS NULL, VALUES(strand_id), students.strand_id),
    enrollment_status = IF(students.enrollment_status IS NULL, VALUES(enrollment_status), students.enrollment_status),
    school_id = IF(students.school_id IS NULL, VALUES(school_id), students.school_id),
    lrn = IF(students.lrn IS NULL, VALUES(lrn), students.lrn),
    gwa = IF(students.gwa IS NULL, VALUES(gwa), students.gwa),
    barangay_id = IF(students.barangay_id IS NULL, VALUES(barangay_id), students.barangay_id),
    sex = IF(students.sex IS NULL, VALUES(sex), students.sex),
    birthday = IF(students.birthday IS NULL, VALUES(birthday), students.birthday),
    email = IF(students.email IS NULL, VALUES(email), students.email),
    contact_number = IF(students.contact_number IS NULL, VALUES(contact_number), students.contact_number),
    -- Generally, DO NOT update created_at on duplicate. Keep the original creation time.
    -- created_at = students.created_at, -- Explicitly keep the old one
    municipality_id = IF(students.municipality_id IS NULL, VALUES(municipality_id), students.municipality_id),
    province_id = IF(students.province_id IS NULL, VALUES(province_id), students.province_id),
    purok = IF(students.purok IS NULL, VALUES(purok), students.purok);