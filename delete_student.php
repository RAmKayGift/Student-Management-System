<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $student_id = $_POST['id'];

    try {
        // Delete from enrolled_students
        $stmt = $conn->prepare("DELETE FROM enrolled_students WHERE student_id = :id");
        $stmt->execute(['id' => $student_id]);

        // Optionally: delete from students too (uncomment if you want)
        // $stmt = $conn->prepare("DELETE FROM students WHERE student_id = :id");
        // $stmt->execute(['id' => $student_id]);

        echo "Student unenrolled successfully.";
    } catch (PDOException $e) {
        echo "Error deleting student: " . $e->getMessage();
    }
}
?>
