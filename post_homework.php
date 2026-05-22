<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['teacher_id'])) {
    die("Not logged in");
}

$teacher_id = $_SESSION['teacher_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$due_date = $_POST['due_date'];
$subject_name = $_POST['subject']; // from hidden field in form

// Get subject_id and grade from teacher
$stmt = $conn->prepare("
    SELECT subject_id, grade FROM teachers WHERE id = :id
");
$stmt->execute([':id' => $teacher_id]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    die("Teacher not found.");
}

$subject_id = $teacher['subject_id'];
$grade = $teacher['grade'];

// File upload handling
$file_path = null;
if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/homework/';
    $file_tmp = $_FILES['pdf_file']['tmp_name'];
    $file_name = basename($_FILES['pdf_file']['name']);
    $target_path = $upload_dir . time() . '_' . $file_name;

    if (move_uploaded_file($file_tmp, $target_path)) {
        $file_path = $target_path;
    } else {
        die("File upload failed.");
    }
}

try {
    $insert = $conn->prepare("
        INSERT INTO homework (teacher_id, subject_id, grade, title, description, due_date, file_path)
        VALUES (:teacher_id, :subject_id, :grade, :title, :description, :due_date, :file_path)
    ");
    $insert->execute([
        ':teacher_id' => $teacher_id,
        ':subject_id' => $subject_id,
        ':grade' => $grade,
        ':title' => $title,
        ':description' => $description,
        ':due_date' => $due_date,
        ':file_path' => $file_path
    ]);

    header("Location: teacher_dashboard.php?success=1");
    exit();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
