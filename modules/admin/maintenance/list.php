<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/MaintenanceSchedule.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

$maintenanceSchedule = new MaintenanceSchedule($conn);
$schedules = $maintenanceSchedule->getAllWithUserDetails();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Schedules - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/admin-navbar.php'; ?>

    <div class="container py-4">
        <h1 class="mb-4">Maintenance Schedules</h1>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Rider Name</th>
                                <th>Service Type</th>
                                <th>Scheduled Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedules as $schedule): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($schedule['fullname']); ?></td>
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
                                <td>
                                    <form method="POST" action="update-status.php" class="d-inline">
                                        <input type="hidden" name="schedule_id" value="<?php echo $schedule['schedule_id']; ?>">
                                        <select name="status" class="form-select form-select-sm d-inline-block w-auto">
                                            <option value="scheduled" <?php echo $schedule['status'] == 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                                            <option value="completed" <?php echo $schedule['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="canceled" <?php echo $schedule['status'] == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../../../includes/admin-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 