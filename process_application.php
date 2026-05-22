<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $action = $_POST['action'];
    
    try {
        $conn->beginTransaction();
        
        if ($action === 'accept') {
            // Update application status
            $stmt = $conn->prepare("UPDATE students SET application_status = 'Accepted' WHERE student_id = ?");
            $stmt->execute([$student_id]);
            
            // Add to enrolled students
            $stmt = $conn->prepare("INSERT INTO enrolled_students (student_id, enrollment_date) VALUES (?, NOW())");
            $stmt->execute([$student_id]);
            
            // You might also want to create a user account for the student here
            
            $message = "Application accepted and student enrolled successfully!";
        } elseif ($action === 'reject') {
            $stmt = $conn->prepare("UPDATE students SET application_status = 'Rejected', rejection_date = NOW() WHERE student_id = ?");
            $stmt->execute([$student_id]);
            
            $message = "Application rejected successfully!";
        }
        
        $conn->commit();
        
        // Redirect back with success message
        header("Location: applications.php?message=" . urlencode($message));
        exit;
        
    } catch (PDOException $e) {
        $conn->rollBack();
        header("Location: applications.php?error=" . urlencode("Error processing application: " . $e->getMessage()));
        exit;
    }
} else {
    header("Location: applications.php");
    exit;
}