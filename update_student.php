<?php
require 'db_connect.php';

// Validate input
$errors = [];

// Check required fields
$required = ['student_id', 'id_number', 'first_name', 'last_name', 'email', 
             'grade_applied_for', 'gender', 'parent_phone', 'parent_email', 'dob'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        $errors[] = "$field is required";
    }
}

// Validate names (letters only)
if (!preg_match('/^[A-Za-z]+$/', $_POST['first_name'])) {
    $errors[] = "First name can only contain letters";
}

if (!preg_match('/^[A-Za-z]+$/', $_POST['last_name'])) {
    $errors[] = "Last name can only contain letters";
}

// Validate grade is one of the allowed options
$allowedGrades = ['8', '9', '10', '11', '12'];
if (!in_array($_POST['grade_applied_for'], $allowedGrades)) {
    $errors[] = "Invalid grade selected";
}

// Validate email format
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !strpos($_POST['email'], '@')) {
    $errors[] = "Invalid email format";
}

if (!filter_var($_POST['parent_email'], FILTER_VALIDATE_EMAIL) || !strpos($_POST['parent_email'], '@')) {
    $errors[] = "Invalid parent email format";
}

// If any errors, return them
if (!empty($errors)) {
    die(implode("<br>", $errors));
}

// Proceed with update if validation passes
try {
    $stmt = $conn->prepare("
        UPDATE students SET 
            first_name = ?, 
            last_name = ?, 
            email = ?, 
            grade_applied_for = ?, 
            gender = ?, 
            parent_phone = ?, 
            parent_email = ?, 
            dob = ?
        WHERE student_id = ?
    ");
    
    $stmt->execute([
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['grade_applied_for'],
        $_POST['gender'],
        $_POST['parent_phone'],
        $_POST['parent_email'],
        $_POST['dob'],
        $_POST['student_id']
    ]);
    
    echo "Student updated successfully";
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>