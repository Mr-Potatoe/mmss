<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/ServiceRequest.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

$serviceRequest = new ServiceRequest($conn);
$requests = $serviceRequest->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Requests - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/admin-navbar.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Service Requests</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Service Type</th>
                                <th>Schedule Date</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Mechanic</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?php echo $request['request_id']; ?></td>
                                <td><?php echo htmlspecialchars($request['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['service_name']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($request['schedule_date'])); ?></td>
                                <td><?php echo htmlspecialchars($request['contact_number']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $request['status'] === 'pending' ? 'warning' : 
                                            ($request['status'] === 'approved' ? 'success' : 
                                            ($request['status'] === 'completed' ? 'primary' : 'danger')); 
                                    ?>">
                                        <?php echo ucfirst($request['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $request['mechanic_name'] ?? 'Not Assigned'; ?></td>
                                <td>
                                    <a href="view.php?id=<?php echo $request['request_id']; ?>" 
                                       class="btn btn-sm btn-primary">View</a>
                                    <?php if ($request['status'] === 'pending'): ?>
                                    <a href="approve.php?id=<?php echo $request['request_id']; ?>" 
                                       class="btn btn-sm btn-success"
                                       onclick="return confirm('Are you sure you want to approve this request?')">
                                        Approve
                                    </a>
                                    <?php endif; ?>
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
