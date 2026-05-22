<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $status = $_POST['status'];

    try {
        $sql = "UPDATE students SET application_status = :status WHERE student_id = :student_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':student_id' => $student_id
        ]);

        echo "<script>alert('Application status updated to $status!'); window.location.href='admin_dashboard.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
