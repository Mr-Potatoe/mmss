<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/ServiceRequest.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'rider') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

$serviceRequest = new ServiceRequest($conn);
$requests = $serviceRequest->getAllByUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Request History - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/rider-navbar.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Service Request History</h1>
            <a href="create.php" class="btn btn-primary">New Request</a>
        </div>

        <?php if (isset($_GET['success']) && $_GET['success'] == 'created'): ?>
            <div class="alert alert-success">Service request created successfully!</div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Service Type</th>
                                <th>Schedule Date</th>
                                <th>Status</th>
                                <th>Mechanic</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['service_name']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($request['schedule_date'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $request['status'] == 'pending' ? 'warning' : 
                                            ($request['status'] == 'approved' ? 'success' : 
                                            ($request['status'] == 'completed' ? 'info' : 'danger')); 
                                    ?>">
                                        <?php echo ucfirst($request['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $request['mechanic_name'] ?? 'Not Assigned'; ?></td>
                                <td><?php echo date('M d, Y', strtotime($request['created_at'])); ?></td>
                                <td>
                                    <a href="view.php?id=<?php echo $request['request_id']; ?>" 
                                       class="btn btn-sm btn-primary">View</a>
                                </td>
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
