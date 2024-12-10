<?php
session_start();
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../models/ServiceRequest.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'rider') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

$serviceRequest = new ServiceRequest($conn);
$pendingRequests = $serviceRequest->countByUserAndStatus($_SESSION['user_id'], 'pending');
$approvedRequests = $serviceRequest->countByUserAndStatus($_SESSION['user_id'], 'approved');
$completedRequests = $serviceRequest->countByUserAndStatus($_SESSION['user_id'], 'completed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Dashboard - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../includes/rider-navbar.php'; ?>

    <div class="container py-4">
        <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Pending Requests</h5>
                        <h2 class="card-text"><?php echo $pendingRequests; ?></h2>
                        <a href="service-requests/history.php" class="text-white">View Details →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Approved Requests</h5>
                        <h2 class="card-text"><?php echo $approvedRequests; ?></h2>
                        <a href="service-requests/history.php" class="text-white">View Details →</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Completed Services</h5>
                        <h2 class="card-text"><?php echo $completedRequests; ?></h2>
                        <a href="service-requests/history.php" class="text-white">View Details →</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Service Requests</h5>
                        <a href="service-requests/create.php" class="btn btn-primary btn-sm">New Request</a>
                    </div>
                    <div class="card-body">
                        <a href="service-requests/history.php" class="btn btn-outline-primary">View All Requests</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Maintenance Schedule</h5>
                        <a href="maintenance/schedule.php" class="btn btn-primary btn-sm">Schedule Service</a>
                    </div>
                    <div class="card-body">
                        <a href="maintenance/history.php" class="btn btn-outline-primary">View Schedule History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../../includes/rider-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
