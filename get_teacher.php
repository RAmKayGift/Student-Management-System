<?php
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM teachers WHERE id = ?");
    $stmt->execute([$id]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($teacher) {
        header('Content-Type: application/json');
        echo json_encode($teacher);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Teacher not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Teacher ID required']);
}
?>