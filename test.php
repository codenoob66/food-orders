<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Pay Food</title>
</head>

<body class="test-page">
    <div class="top-section">
        <h1>Welcome!</h1>
    </div>

    <?php
    $paymentsFile = __DIR__ . '/data/payments.json';
    $payments = file_exists($paymentsFile) ? json_decode(file_get_contents($paymentsFile), true) : [];
    ?>

    <?php if (!empty($payments)): ?>
        <div class="table-card">
            <h2>Payment List</h2>
            <p class="table-subtitle">Upload your proof of payment below. Questions? Message Rafael Vincent Cordova on Slack.</p>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Food</th>
                        <th>Total</th>
                        <th>Proof of Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $person): ?>
                        <tr>
                            <td data-label="Name"><?= htmlspecialchars($person['name']) ?></td>
                            <td data-label="Food"><?= htmlspecialchars($person['food']) ?></td>
                            <td data-label="Total">₱<?= number_format($person['amount'], 2) ?></td>
                            <td data-label="Proof of Payment">
                                <form class="inline-upload" action="upload.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="name" value="<?= htmlspecialchars($person['name']) ?>">
                                    <input type="file" name="myFile" accept="image/*" required>
                                    <button type="submit">Upload</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="form-card">
            <p>No payment records found.</p>
        </div>
    <?php endif; ?>
</body>

</html>
