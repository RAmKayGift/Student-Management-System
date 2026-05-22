<?php
$password = 'admin@123'; // You can change this to any password you want
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo "<strong>Plain Password:</strong> " . $password . "<br>";
echo "<strong>Hashed Password:</strong> " . $hashedPassword;
?>
