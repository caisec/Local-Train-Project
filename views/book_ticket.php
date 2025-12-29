<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/local_train_app/public/assets/css/style.css">

</head>
<body>
    <?php require_once 'partials/header.php'; ?>

    <div class="container mt-5">
        <h2>Book Train Ticket</h2>
        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label>From Station</label>
                <select name="from_station" class="form-control" required>
                    <?php foreach ($stations as $station): ?>
                        <option value="<?php echo $station['id']; ?>"><?php echo $station['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>To Station</label>
                <select name="to_station" class="form-control" required>
                    <?php foreach ($stations as $station): ?>
                        <option value="<?php echo $station['id']; ?>"><?php echo $station['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Ticket Type</label>
                <select name="ticket_type" class="form-control">
                    <option value="one_way">One Way</option>
                    <option value="return">Return</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Class</label>
                <select name="class" class="form-control">
                    <option value="second">Second Class</option>
                    <option value="first">First Class</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Book</button>
        </form>
        <a href="index.php?action=dashboard" class="btn btn-secondary mt-2">Back to Dashboard</a>
    </div>
    <script src="../public/assets/js/main.js"></script>

</body>
</html>