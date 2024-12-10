<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/MaintenanceSchedule.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'rider') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

$maintenanceSchedule = new MaintenanceSchedule($conn);
$schedules = $maintenanceSchedule->getAllByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance History - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/rider-navbar.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Maintenance History</h1>
            <a href="schedule.php" class="btn btn-primary">Schedule Maintenance</a>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 'scheduled'): ?>
            <div class="alert alert-success">Maintenance scheduled successfully!</div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Service Type</th>
                                <th>Scheduled Date</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedules as $schedule): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($schedule['service_name']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($schedule['scheduled_date'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $schedule['status'] == 'scheduled' ? 'warning' : 
                                            ($schedule['status'] == 'completed' ? 'success' : 'danger'); 
                                    ?>">
                                        <?php echo ucfirst($schedule['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($schedule['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../../../includes/rider-footer.php'; ?>
</body>
</html>
