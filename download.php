<?php
// Set the upload directory
$uploadDir = __DIR__ . '/uploads/';

// Validate and sanitize input
if (!isset($_GET['file'])) {
    die("File not specified.");
}

$filename = basename($_GET['file']); // Prevent directory traversal

$filePath = $uploadDir . $filename;

// Check if file exists
if (!file_exists($filePath)) {
    die("Student did not upload the file.");
}

// Get MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $filePath);
finfo_close($finfo);

// Set headers to display the file in browser if possible
header("Content-Type: $mimeType");
header("Content-Disposition: inline; filename=\"$filename\"");
header("Content-Length: " . filesize($filePath));
readfile($filePath);
exit;
