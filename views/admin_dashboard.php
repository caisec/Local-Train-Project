<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/local_train_app/public/assets/css/style.css">


    <style>
        body { background: #f8f9fa; }
        .card { border-radius: 10px; box-shadow: 0 0 10px #000000ff; }
        .logo-box img { max-width: 200px; border: 1px solid #ddd; padding: 10px; background: #fff; }
    </style>
</head>
<body>
    <?php require_once 'partials/header.php'; ?>

<div class="container mt-4">

    <h2 class="mb-4">🚆 Admin Dashboard</h2>

    <!-- Alerts -->
    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="row g-4">

        <!-- Recharge -->
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Recharge User</h5>
                <form method="POST" action="index.php?action=recharge">
                    <select name="user_id" class="form-control mb-2" required>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> - $<?= number_format($user['balance'],2) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="amount" class="form-control mb-2" placeholder="Amount" step="0.01" required>
                    <button class="btn btn-success w-100">Recharge</button>
                </form>
            </div>
        </div>

        <!-- Add Schedule -->
        <div class="col-md-6">
            <div class="card p-3">
                <h5>Add Schedule</h5>
                <form method="POST" action="index.php?action=addSchedule">
                    <select name="train_id" class="form-control mb-2" required>
                        <?php foreach ($trains as $train): ?>
                            <option value="<?= $train['id'] ?>"><?= htmlspecialchars($train['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="datetime-local" name="departure_time" class="form-control mb-2" required>
                    <input type="datetime-local" name="expected_arrival" class="form-control mb-2" required>
                    <select name="from_station" class="form-control mb-2" required>
                        <option value="1">Cairo</option>
                        <option value="2">Alexandria</option>
                        <option value="3">Giza</option>
                    </select>
                    <select name="to_station" class="form-control mb-2" required>
                        <option value="1">Cairo</option>
                        <option value="2">Alexandria</option>
                        <option value="3">Giza</option>
                    </select>
                    <button class="btn btn-primary w-100">Add</button>
                </form>
            </div>
        </div>

<script>
document.getElementById('logo-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('logo-preview');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
    <!-- Tickets Table -->
    <div class="card p-3 mt-4">
        <h5>Tickets Management (<?= $total_tickets ?>)</h5>
        <table class="table table-striped mt-2">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Class</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($tickets)): ?>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= $ticket['id'] ?></td>
                        <td><?= htmlspecialchars($ticket['user_name'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($ticket['ticket_type']) ?></td>
                        <td><?= htmlspecialchars($ticket['class']) ?></td>
                        <td>$<?= number_format($ticket['price'],2) ?></td>
                        <td>
                            <form method="POST" action="index.php?action=delete_ticket" onsubmit="return confirm('Delete this ticket?');">
                                <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No tickets</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="index.php?action=schedule" class="btn btn-info">View Schedule</a>
        <a href="index.php?action=logout" class="btn btn-danger">Logout</a>
    </div>

</div>
<script src="../public/assets/js/main.js"></script>

</body>
</html>
