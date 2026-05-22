<?php
$host = "localhost";
$dbname = "school_management_system";
$user = "postgres";
$password = "Fulufhelo1";  // PostgreSQL password

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
