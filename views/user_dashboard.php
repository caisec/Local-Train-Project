<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/local_train_app/public/assets/css/style.css">


</head>

<body>
    <?php require_once 'partials/header.php'; ?>

    <div class="container mt-5">
        <h2>Welcome, <?php echo $_SESSION['user_name'] ?? 'User'; ?>!</h2>
        <a href="index.php?action=book" class="btn btn-primary">Book Ticket</a>
        <a href="index.php?action=schedule" class="btn btn-info">View Train Schedule</a>
        <a href="index.php?action=logout" class="btn btn-secondary">Logout</a>
    </div>
    <script src="../public/assets/js/main.js"></script>

</body>
</html>