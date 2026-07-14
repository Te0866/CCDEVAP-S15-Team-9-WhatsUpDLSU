<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WhatsUpDLSU</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/darkmode.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php require_once __DIR__ . '/../../navbar.php'; ?>
    <main class="dashboard-layout">
        <?php echo $content; ?>
    </main>
    <script src="js/dashboard.js"></script>
    <script src="js/darkmode.js"></script>
</body>
</html>
