<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/local_train_app/public/assets/css/style.css">
    
</head>
<body>
    <?php require_once 'partials/header.php'; ?>

    <div class="container mt-5">
        <h2>Login</h2>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php?action=login"  class="needs-validation">  <!-- التصحيح: action للـ router -->
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <a href="index.php?action=register">No account? Register</a>  <!-- التصحيح: link للـ router -->
    </div>
    <script src="../public/assets/js/main.js"></script>

</body>
</html>