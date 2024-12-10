<?php
session_start();
require_once '../../config/database.php';
require_once '../../models/ServiceRequest.php';
require_once '../../models/Mechanic.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

$serviceRequest = new ServiceRequest($conn);
$mechanic = new Mechanic($conn);

$pendingRequests = $serviceRequest->countByStatus('pending');
$approvedRequests = $serviceRequest->countByStatus('approved');
$completedRequests = $serviceRequest->countByStatus('completed');
$activeMechanics = count($mechanic->getActive());
$recentRequests = $serviceRequest->getRecent(5);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../includes/admin-navbar.php'; ?>

    <div class="container py-4">
        <h1 class="mb-4">Admin Dashboard</h1>
        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Pending Requests</h5>
                        <p class="card-text h2"><?php echo $pendingRequests; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Approved Requests</h5>
                        <p class="card-text h2"><?php echo $approvedRequests; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Completed Requests</h5>
                        <p class="card-text h2"><?php echo $completedRequests; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h5 class="card-title">Active Mechanics</h5>
                        <p class="card-text h2"><?php echo $activeMechanics; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="h5 mb-0">Recent Service Requests</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentRequests as $request): ?>
                            <tr>
                                <td><?php echo $request['request_id']; ?></td>
                                <td><?php echo htmlspecialchars($request['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['customer_name']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $request['status'] === 'pending' ? 'warning' : 
                                        ($request['status'] === 'approved' ? 'success' : 'primary'); ?>">
                                        <?php echo ucfirst($request['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($request['schedule_date'])); ?></td>
                                <td>
                                    <a href="service-requests/view.php?id=<?php echo $request['request_id']; ?>" 
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

    <?php include_once '../../includes/admin-footer.php'; ?>
</body>
</html>
