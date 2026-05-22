<?php
require 'db_connect.php';

// Fetch all announcements
$announcements = $conn->query("SELECT message, created_at FROM announcements ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon.png" type="image/png">
    <title>All Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>All Announcements</h2>
        <a href="index.php" class="btn btn-secondary mb-3">Back to Home</a>
        <?php foreach ($announcements as $announcement): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">School Announcement</h5>
                    <p class="card-text"><?= htmlspecialchars($announcement['message']); ?></p>
                    <small class="text-muted">Posted on: <?= date("F j, Y", strtotime($announcement['created_at'])); ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
