<?php
require 'db_connect.php';

function showErrorAndExit($message) {
    echo '<!DOCTYPE html>
    <html>
    <head>
    <link rel="icon" href="images/favicon.png" type="image/png">
        <title>Error</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
            .error-box { 
                border: 1px solid #ff6b6b; 
                background-color: #ffebee; 
                padding: 20px; 
                max-width: 500px; 
                margin: 0 auto;
                border-radius: 5px;
            }
            button { 
                background-color: #4CAF50; 
                color: white; 
                padding: 10px 20px; 
                border: none; 
                border-radius: 4px; 
                cursor: pointer;
                margin-top: 20px;
            }
            button:hover { background-color: #45a049; }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h2>Error</h2>
            <p>'.$message.'</p>
            <button onclick="window.history.back()">Go Back</button>
        </div>
    </body>
    </html>';
    exit();
}

// Function to handle file uploads
function uploadFile($fileInputName, $uploadDir = 'uploads/') {
    if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $fileTmpPath = $_FILES[$fileInputName]['tmp_name'];
    $fileName = basename($_FILES[$fileInputName]['name']);
    $fileSize = $_FILES[$fileInputName]['size'];
    $fileType = mime_content_type($fileTmpPath);

    if ($fileType !== 'application/pdf' || $fileSize > 5 * 1024 * 1024) {
        return false;
    }

    // Create upload directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $destination = $uploadDir . uniqid() . "_" . $fileName;

    if (move_uploaded_file($fileTmpPath, $destination)) {
        return $destination;
    }

    return false;
}

// Upload files
$studentIdProofPath = uploadFile('studentIdProof');
$guardianIdProofPath = uploadFile('guardianIdProof');
$academicRecordsPath = uploadFile('academicRecords');

// Check for any failed uploads
if (!$studentIdProofPath || !$guardianIdProofPath || !$academicRecordsPath) {
    showErrorAndExit("Invalid file upload. Please ensure all files are PDFs under 5MB.");
}

// Sanitize inputs
$idNumber         = $_POST['idNumber'];
$firstName        = $_POST['firstName'];
$middleName       = $_POST['middleName'] ?? null;
$lastName         = $_POST['lastName'];
$dob              = $_POST['dob'];
$gender           = $_POST['gender'] ?? null;
$address          = $_POST['address'];
$parentPhone      = $_POST['parentPhone'];
$parentEmail      = $_POST['parentEmail'];
$emergencyContact = $_POST['emergencyContact'];
$grade            = $_POST['grade'];
$previousSchool   = $_POST['previousSchool'] ?? null;
$allergies        = $_POST['allergies'] ?? null;
$email            = $_POST['email'];
$password         = $_POST['password'];
$confirmPassword  = $_POST['confirmPassword'];
$consent          = isset($_POST['consent']) ? true : false;

// Validate passwords match
if ($password !== $confirmPassword) {
    showErrorAndExit("Passwords do not match.");
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // First check if ID number exists (we still want this to be unique)
    $checkStmt = $conn->prepare("SELECT id_number FROM students WHERE id_number = :id_number");
    $checkStmt->execute([':id_number' => $idNumber]);
    
    if ($checkStmt->fetch()) {
        showErrorAndExit("This ID number is already registered. Please use a different one or contact support if you believe this is an error.");
    }

    // Insert the student record (email uniqueness is no longer enforced)
    $stmt = $conn->prepare("
        INSERT INTO students (
            id_number, first_name, middle_name, last_name, dob, gender,
            student_id_proof, address, parent_phone, parent_email,
            guardian_id_proof, emergency_contact, grade_applied_for, previous_school,
            academic_records, allergies, email, password, consent
        ) VALUES (
            :id_number, :first_name, :middle_name, :last_name, :dob, :gender,
            :student_id_proof, :address, :parent_phone, :parent_email,
            :guardian_id_proof, :emergency_contact, :grade, :previous_school,
            :academic_records, :allergies, :email, :password, :consent
        )
    ");

    $stmt->execute([
        ':id_number'         => $idNumber,
        ':first_name'        => $firstName,
        ':middle_name'       => $middleName,
        ':last_name'         => $lastName,
        ':dob'               => $dob,
        ':gender'            => $gender,
        ':student_id_proof'  => $studentIdProofPath,
        ':address'           => $address,
        ':parent_phone'      => $parentPhone,
        ':parent_email'      => $parentEmail,
        ':guardian_id_proof' => $guardianIdProofPath,
        ':emergency_contact' => $emergencyContact,
        ':grade'             => $grade,
        ':previous_school'   => $previousSchool,
        ':academic_records'  => $academicRecordsPath,
        ':allergies'         => $allergies,
        ':email'             => $email,
        ':password'          => $hashedPassword,
        ':consent'           => $consent
    ]);

    // Start session and log the student in automatically
    session_start();
    $_SESSION['id_number'] = $idNumber;
    $_SESSION['first_name'] = $firstName;
    $_SESSION['student_name'] = $firstName . ' ' . $lastName;

    // Success message with similar styling
    echo '<!DOCTYPE html>
    <html>
    <head>
    <link rel="icon" href="images/favicon.png" type="image/png">
        <title>Success</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
            .success-box { 
                border: 1px solid #4CAF50; 
                background-color: #e8f5e9; 
                padding: 20px; 
                max-width: 500px; 
                margin: 0 auto;
                border-radius: 5px;
            }
            button { 
                background-color: #4CAF50; 
                color: white; 
                padding: 10px 20px; 
                border: none; 
                border-radius: 4px; 
                cursor: pointer;
                margin-top: 20px;
            }
            button:hover { background-color: #45a049; }
        </style>
    </head>
    <body>
        <div class="success-box">
            <h2>Success</h2>
            <p>Enrollment submitted successfully!</p>
            <button onclick="window.location.href=\'student_dashboard.php\'">Go to Dashboard</button>
            <button onclick="window.location.href=\'index.php\'">Return Home</button>
        </div>
    </body>
    </html>';

} catch (PDOException $e) {
    // Check for duplicate entry error (MySQL error code 1062)
    if ($e->errorInfo[1] == 1062) {
        // This should only trigger for ID number duplicates now
        showErrorAndExit("This ID number is already registered. Please use a different one or contact support if you believe this is an error.");
    } else {
        showErrorAndExit("Database operation failed. Please try again. Error: " . $e->getMessage());
    }
}
?>