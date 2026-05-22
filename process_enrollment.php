<?php
// Enable error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = 'localhost';
$dbname = 'school_management_system';
$user = 'postgres';
$pass = 'Fulufhelo1';

// Connect to database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Helper function to move and validate file
function savePdfFile($fileInput, $folder = 'uploads') {
    if (!isset($_FILES[$fileInput]) || $_FILES[$fileInput]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $file = $_FILES[$fileInput];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($ext !== 'pdf') {
        return null;
    }

    if ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
        return null;
    }

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $filename = uniqid($fileInput . "_") . ".pdf";
    $destination = $folder . "/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $destination;
    }

    return null;
}

// Collect and sanitize inputs
function getInput($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

$idNumber       = getInput('idNumber');
$firstName      = getInput('firstName');
$middleName     = getInput('middleName');
$lastName       = getInput('lastName');
$dob            = getInput('dob');
$gender         = getInput('gender');
$address        = getInput('address');
$parentPhone    = getInput('parentPhone');
$parentEmail    = getInput('parentEmail');
$emergencyContact = getInput('emergencyContact');
$grade          = getInput('grade');
$previousSchool = getInput('previousSchool');
$allergies      = getInput('allergies');
$email          = getInput('email');
$password       = password_hash(getInput('password'), PASSWORD_DEFAULT); // Hashed
$consent        = isset($_POST['consent']) ? 1 : 0;

// Handle file uploads
$studentIdPath     = savePdfFile('studentIdProof');
$guardianIdPath    = savePdfFile('guardianIdProof');
$academicRecordsPath = savePdfFile('academicRecords');

// Validate required file uploads
if (!$studentIdPath || !$guardianIdPath || !$academicRecordsPath) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'One or more required files are missing or invalid']);
    exit;
}

// Insert into database
try {
    $stmt = $pdo->prepare("
        INSERT INTO enrollments (
            id_number, first_name, middle_name, last_name, dob, gender,
            address, parent_phone, parent_email, emergency_contact, grade,
            previous_school, academic_records, allergies, email, password,
            consent, student_id_proof, guardian_id_proof
        ) VALUES (
            :idNumber, :firstName, :middleName, :lastName, :dob, :gender,
            :address, :parentPhone, :parentEmail, :emergencyContact, :grade,
            :previousSchool, :academicRecords, :allergies, :email, :password,
            :consent, :studentIdProof, :guardianIdProof
        )
    ");

    $stmt->execute([
        ':idNumber'        => $idNumber,
        ':firstName'       => $firstName,
        ':middleName'      => $middleName,
        ':lastName'        => $lastName,
        ':dob'             => $dob,
        ':gender'          => $gender,
        ':address'         => $address,
        ':parentPhone'     => $parentPhone,
        ':parentEmail'     => $parentEmail,
        ':emergencyContact'=> $emergencyContact,
        ':grade'           => $grade,
        ':previousSchool'  => $previousSchool,
        ':academicRecords' => $academicRecordsPath,
        ':allergies'       => $allergies,
        ':email'           => $email,
        ':password'        => $password,
        ':consent'         => $consent,
        ':studentIdProof'  => $studentIdPath,
        ':guardianIdProof' => $guardianIdPath,
    ]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database insert failed']);
}
?>
