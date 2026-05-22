<?php
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute delete
    $stmt = $conn->prepare("DELETE FROM teachers WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // Redirect back to the teachers page
    header("Location: crazy.php");
    exit;
} else {
    echo "Invalid request.";
}
