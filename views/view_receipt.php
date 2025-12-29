<?php
require_once __DIR__ . '/../vendor/autoload.php';

ob_start();

// Autoload check with logging
$autoload_path = '../vendor/autoload.php';
if (file_exists($autoload_path)) {
    require_once $autoload_path;
    error_log("Autoload loaded successfully.");
} else {
    error_log("Autoload not found at: " . $autoload_path);
    die('Error: Run "composer install" in root.');
}


// Use statements for new API (v6+)
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Writer\PngWriter;

// Check $receipt
if (!isset($receipt) || !is_array($receipt)) {
    $_SESSION['error'] = 'Receipt not found.';
    header('Location: ../index.php?action=dashboard');
    exit;
}

// Generate QR with Builder (new API)
$qr_path = '';

try {
    $result = Builder::create()
        ->writer(new PngWriter())
        ->data(
            "Ticket ID: {$receipt['id']}\n" .
            "User: " . ($_SESSION['user_name'] ?? 'Unknown')
        )
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(new ErrorCorrectionLevelLow())
        ->size(200)
        ->margin(10)
        ->build();

    // ✅ MUST be inside public
    $qr_dir = __DIR__ . '/../public/temp';
    if (!is_dir($qr_dir)) {
        mkdir($qr_dir, 0755, true);
    }

    $qr_path = 'temp/qr_' . $receipt['id'] . '.png';
    $result->saveToFile(__DIR__ . '/../public/' . $qr_path);

} catch (Throwable $e) {
    error_log('QR Error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/local_train_app/public/assets/css/style.css">

</head>
<body>
    <?php require_once 'partials/header.php'; ?>

<div class="container mt-5">
        <h2>Ticket Receipt</h2>
        <div class="text-center mb-3">
    
</div>
<img src="../assets/logo.png?t=<?php echo time(); ?>" alt="Company Logo" class="img-fluid mb-3" style="max-width: 200px;">
        <div class="card">
            <div class="card-body">
                <p><strong>Ticket ID:</strong> <?php echo htmlspecialchars($receipt['id']); ?></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($receipt['ticket_type']); ?></p>
                <p><strong>Class:</strong> <?php echo htmlspecialchars($receipt['class']); ?></p>
                <p><strong>Price:</strong> $<?php echo number_format($receipt['price'], 2); ?></p>
                <p><strong>Booking Date:</strong> <?php echo date('Y-m-d H:i:s', strtotime($receipt['booking_date'])); ?></p>
                <?php if ($qr_path && file_exists(__DIR__ . '/../public/' . $qr_path)): ?>
                 <img src="../<?php echo $qr_path; ?>" alt="QR Code" class="img-fluid mt-3">
                 <?php else: ?>
                   <p class="text-danger">QR generation failed.</p>
<?php endif; ?>

            </div>
        </div>
        <a href="index.php?action=dashboard" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div>
    <script src="../public/assets/js/main.js"></script>

</body>
</html>

<?php
if (isset($qr_path) && $qr_path && file_exists($qr_path)) {
    unlink($qr_path);
}
ob_end_flush();
?>