<?php

/**
 * Dump and die (for quick debugging).
 *
 * @param mixed $value
 */
function dd($value): void
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

/**
 * Check if current URI matches given URI.
 *
 * @param string $uri
 *
 * @return bool
 */
function getURI(string $uri): bool
{
    return $_SERVER["REQUEST_URI"] === $uri;
}

/**
 * Check if a student's profile is complete with all required fields filled.
 *
 * @param array $student The student data array from the database
 * @return bool Returns true if profile is complete, false otherwise
 */
function isProfileComplete(array $student): bool
{
    // Define which fields are required for a complete profile
    $requiredFields = [
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'sex',
        'birthday',
        'lrn',
        'gwa',
        'school_id',
        'strand_id',
        'first_preference_id',
        'barangay_id',
        'municipality_id',
        'province_id'
    ];
    
    // Check if any required field is empty
    foreach ($requiredFields as $field) {
        if (!isset($student[$field]) || $student[$field] === null || $student[$field] === '') {
            return false;
        }
    }
    
    return true;
}