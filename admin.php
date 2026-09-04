<?php
require_once __DIR__ . '/auth.php';
require_auth();

$uploadsLog = __DIR__ . '/data/uploads.json';
$uploads = file_exists($uploadsLog) ? json_decode(file_get_contents($uploadsLog), true) : [];
$uploads = array_reverse($uploads); // newest first
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <div class="top-section">
        <h1>Admin Dashboard</h1>
        <a href="logout.php" class="logout-link">Logout</a>
    </div>

    <?php if (empty($uploads)): ?>
        <div class="empty-state">
            <p>No uploads yet.</p>
        </div>
    <?php else: ?>
        <div class="uploads-grid">
            <?php foreach ($uploads as $upload): ?>
                <div class="upload-card">
                    <div class="upload-preview">
                        <a href="upload/<?= htmlspecialchars($upload['filename']) ?>" target="_blank">
                            <img src="upload/<?= htmlspecialchars($upload['filename']) ?>" alt="Proof of payment">
                        </a>
                    </div>
                    <div class="upload-info">
                        <span class="upload-name"><?= htmlspecialchars($upload['name']) ?></span>

                        <span class="upload-date"><?= htmlspecialchars($upload['uploaded_at']) ?></span>
                    </div>
                    <form method="POST" action="delete.php" onsubmit="return confirm('Delete this upload?')">
                        <input type="hidden" name="filename" value="<?= htmlspecialchars($upload['filename']) ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>

</html>