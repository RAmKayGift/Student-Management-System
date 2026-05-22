<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $subject = $_POST["subject"];
    $grade = $_POST["grade"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO teachers (first_name, last_name, email, phone, address, subject_id, grade, password)
                VALUES (:first_name, :last_name, :email, :phone, :address,
                        (SELECT subject_id FROM subjects WHERE subject_name = :subject),
                        :grade, :password)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':subject' => $subject,
            ':grade' => $grade,
            ':password' => $password
        ]);

        echo "<script>alert('Teacher added successfully.'); window.location.href = 'admin_dashboard.php';</script>";

    } catch (PDOException $e) {
        echo "Error adding teacher: " . $e->getMessage();
    }
}
?>
