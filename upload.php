<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Define paths
    $rootDirectory = __DIR__ . '/upload/';
    $uploadsLog = __DIR__ . '/data/uploads.json';
    $user = trim($_POST['name'] ?? '');

    // 2. Validate required fields
    if ($user === '' || !isset($_FILES['myFile']) || $_FILES['myFile']['error'] !== UPLOAD_ERR_OK) {
        $status = 'error';
        $message = "Please provide your name and select a file.";
        goto render;
    }

    // 3. Validate file size (3MB max)
    $maxSize = 3 * 1024 * 1024; // 3MB in bytes
    if ($_FILES['myFile']['size'] > $maxSize) {
        $status = 'error';
        $message = "File is too large. Maximum size is 3MB.";
        goto render;
    }

    // 4. Validate file type (images only)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['myFile']['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mimeType, $allowedTypes)) {
        $status = 'error';
        $message = "Only JPG, PNG, GIF, and WebP images are allowed.";
        goto render;
    }

    $user = ucfirst($user);

    // 5. Sanitize the filename
    $originalName = basename($_FILES['myFile']['name']);
    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    // 6. Block executable PHP files
    if ($fileExtension === 'php') {
        die("Error: Uploading PHP files is forbidden for security reasons.");
    }

    // 7. Generate unique filename: Name_timestamp.ext
    $timestamp = date('Ymd_His');
    $safeName = preg_replace('/[^a-zA-Z0-9]/', '', $user);
    $newFileName = "{$safeName}_{$timestamp}.{$fileExtension}";
    $targetFile = $rootDirectory . $newFileName;

    // 8. Move the uploaded file
    if (move_uploaded_file($_FILES['myFile']['tmp_name'], $targetFile)) {
        // 9. Log the upload to JSON (with file lock to prevent race conditions)
        $fp = fopen($uploadsLog, 'c');
        flock($fp, LOCK_EX);
        $uploads = filesize($uploadsLog) > 0 ? json_decode(file_get_contents($uploadsLog), true) : [];
        $uploads[] = [
            'name' => $user,
            'filename' => $newFileName,
            'uploaded_at' => date('Y-m-d H:i:s')
        ];
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($uploads, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
        fclose($fp);

        $status = 'success';
        $message = "Thank you for paying, {$user}!";
    } else {
        $status = 'error';
        $message = "There was an error uploading your file.";
    }
} else {
    header('Location: index.php');
    exit;
}

render:
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Upload Result</title>
</head>
<body>
    <div class="top-section">
        <h1><?= $status === 'success' ? '✅' : '❌' ?></h1>
    </div>
    <div class="form-card">
        <p class="status-message status-<?= $status ?>"><?= htmlspecialchars($message) ?></p>
        <a href="index.php" class="back-link">Back to Home</a>
    </div>
</body>
</html>
