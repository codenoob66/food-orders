<?php
require_once __DIR__ . '/auth.php';
require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

$filename = $_POST['filename'] ?? '';
$uploadsLog = __DIR__ . '/data/uploads.json';

if (empty($filename)) {
    header('Location: admin.php');
    exit;
}

// Sanitize — only allow basename, no directory traversal
$filename = basename($filename);
$filePath = __DIR__ . '/upload/' . $filename;

// Remove the file from disk
if (file_exists($filePath)) {
    unlink($filePath);
}

// Remove the entry from uploads.json
$uploads = file_exists($uploadsLog) ? json_decode(file_get_contents($uploadsLog), true) : [];
$uploads = array_filter($uploads, fn($entry) => $entry['filename'] !== $filename);
$uploads = array_values($uploads); // re-index
file_put_contents($uploadsLog, json_encode($uploads, JSON_PRETTY_PRINT));

header('Location: admin.php');
exit;
