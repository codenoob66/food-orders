<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Pay Food</title>
</head>

<body>
    <div class="top-section">
        <h1>Welcome!</h1>
    </div>
    <div class="form-card">
        <p>Please upload your proof of payment here thank you! Have questions? Please feel free to message me on slack
            Rafael Vincent Cordova</p>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>
            <label for="myFile">Photo</label>
            <div class="container">
                <input type="file" name="myFile" id="myFile" required>
                <button id="upload-button" type="submit">Upload File</button>
            </div>
        </form>
    </div>

    <?php
    $paymentsFile = __DIR__ . '/data/payments.json';
    $payments = file_exists($paymentsFile) ? json_decode(file_get_contents($paymentsFile), true) : [];
    ?>

    <?php if (!empty($payments)): ?>
        <div class="table-card">
            <h2>Payment List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Food</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $person): ?>
                        <tr>
                            <td><?= htmlspecialchars($person['name']) ?></td>
                            <td><?= htmlspecialchars($person['food']) ?></td>
                            <td>₱<?= number_format($person['amount'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</body>

</html>