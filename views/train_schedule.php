<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Train Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/local_train_app/public/assets/css/style.css">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <body class="schedule-page">

    <?php require_once 'partials/header.php'; ?>

    <div class="container mt-5">
        <h2>Train Schedule </h2>
        <table class="table table-striped" id="scheduleTable">
            <thead>
                <tr>
                    <th>Train</th> 
                    <th>From</th>
                    <th>To</th>
                    <th>Expected Departure</th>
                    <th>Expected Arrival</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $sch): ?>
                <tr>
                    <td><?php echo $sch['train_name']; ?></td>
                    <td><?php echo $sch['from_name']; ?></td>
                    <td><?php echo $sch['to_name']; ?></td>
                    <td><?php echo $sch['departure_time']; ?></td>
                    <td><?php echo $sch['expected_arrival']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

      

        
        
    </div>
    <script src="../public/assets/js/main.js"></script>


</body>
</html>