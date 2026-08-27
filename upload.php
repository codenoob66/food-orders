<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Define paths
    $rootDirectory = $_SERVER['DOCUMENT_ROOT'] . '/pay-food/upload/';
    $uploadsLog = __DIR__ . '/data/uploads.json';
    $user = trim($_POST['name'] ?? '');

    // 2. Validate required fields
    if ($user === '' || !isset($_FILES['myFile']) || $_FILES['myFile']['error'] !== UPLOAD_ERR_OK) {
        $status = 'error';
        $message = "Please provide your name and select a file.";
        // Skip to rendering the result page
        goto render;
    }

    $user = ucfirst($user);

    // 4. Sanitize the filename
    $originalName = basename($_FILES['myFile']['name']);
    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    // 5. Block executable PHP files
    if ($fileExtension === 'php') {
        die("Error: Uploading PHP files is forbidden for security reasons.");
    }

    // 6. Generate unique filename: Name_timestamp.ext
    $timestamp = date('Ymd_His');
    $safeName = preg_replace('/[^a-zA-Z0-9]/', '', $user);
    $newFileName = "{$safeName}_{$timestamp}.{$fileExtension}";
    $targetFile = $rootDirectory . $newFileName;

    // 7. Move the uploaded file
    if (move_uploaded_file($_FILES['myFile']['tmp_name'], $targetFile)) {
        // 8. Log the upload to JSON
        $uploads = file_exists($uploadsLog) ? json_decode(file_get_contents($uploadsLog), true) : [];
        $uploads[] = [
            'name' => $user,
            'filename' => $newFileName,
            'uploaded_at' => date('Y-m-d H:i:s')
        ];
        file_put_contents($uploadsLog, json_encode($uploads, JSON_PRETTY_PRINT));

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
