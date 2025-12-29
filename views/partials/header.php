<nav class="navbar navbar-dark bg-dark">
    <div class="container">
     <a class="navbar-brand" href="/local_train_app/public/index.php?action=dashboard">
     🚆 Local Train
     </a>


        <?php if (isset($_SESSION['user_name'])): ?>
            <span class="text-white">
                Hello, <?= htmlspecialchars($_SESSION['user_name']) ?>
            </span>
        <?php endif; ?>
    </div>
</nav>
