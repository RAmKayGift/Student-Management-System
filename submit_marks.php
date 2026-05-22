<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id = $_SESSION['teacher_id'];
    $student_id = $_POST['student_id'] ?? null;
    $subject = $_POST['subject'] ?? null;
    $assessment_type = $_POST['assessment_type'] ?? null;
    $marks = $_POST['marks'] ?? null;

    if (!$student_id || !$subject || !$assessment_type || $marks === null) {
        die("Missing data. Please fill in all fields.");
    }

    // Validate marks range
    if (!is_numeric($marks) || $marks < 0 || $marks > 100) {
        die("Invalid marks. Must be between 0 and 100.");
    }

    try {
        // Check if marks already exist for this student, subject, and assessment type
        $check_stmt = $conn->prepare("
            SELECT id FROM marks 
            WHERE student_id = :student_id AND subject = :subject AND assessment_type = :assessment_type
        ");
        $check_stmt->execute([
            ':student_id' => $student_id,
            ':subject' => $subject,
            ':assessment_type' => $assessment_type
        ]);

        if ($check_stmt->rowCount() > 0) {
            // Update existing marks
            $update_stmt = $conn->prepare("
                UPDATE marks 
                SET marks = :marks 
                WHERE student_id = :student_id AND subject = :subject AND assessment_type = :assessment_type
            ");
            $update_stmt->execute([
                ':marks' => $marks,
                ':student_id' => $student_id,
                ':subject' => $subject,
                ':assessment_type' => $assessment_type
            ]);
        } else {
            // Insert new marks
            $insert_stmt = $conn->prepare("
                INSERT INTO marks (student_id, subject, assessment_type, marks) 
                VALUES (:student_id, :subject, :assessment_type, :marks)
            ");
            $insert_stmt->execute([
                ':student_id' => $student_id,
                ':subject' => $subject,
                ':assessment_type' => $assessment_type,
                ':marks' => $marks
            ]);
        }

        // Redirect back to dashboard
        header("Location: teacher_dashboard.php?success=1");
        exit();

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}
?>
