<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $grade = $_POST['grade'];
    $subject_id = $_POST['subject_id'];
    
    try {
        $stmt = $conn->prepare("UPDATE teachers SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, grade = ?, subject_id = ? WHERE id = ?");
        $stmt->execute([$firstName, $lastName, $email, $phone, $address, $grade, $subject_id, $id]);
        
        echo "Teacher updated successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>