<?php
require 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = :id");
        $stmt->execute(['id' => $id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            echo json_encode($student);
        } else {
            echo json_encode(["error" => "Student not found"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
