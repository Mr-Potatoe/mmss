<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/ServiceRequest.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'rider') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: history.php');
    exit();
}

$serviceRequest = new ServiceRequest($conn);
$request = $serviceRequest->getByIdAndUser($_GET['id'], $_SESSION['user_id']);

if (!$request) {
    header('Location: history.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Service Request - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/rider-navbar.php'; ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Service Request Details</h2>
                        <a href="history.php" class="btn btn-secondary">Back to History</a>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Service Information</h5>
                            <p><strong>Service Type:</strong> <?php echo htmlspecialchars($request['service_name']); ?></p>
                            <p><strong>Schedule Date:</strong> <?php echo date('M d, Y', strtotime($request['schedule_date'])); ?></p>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-<?php 
                                    echo $request['status'] == 'pending' ? 'warning' : 
                                        ($request['status'] == 'approved' ? 'success' : 
                                        ($request['status'] == 'completed' ? 'info' : 'danger')); 
                                ?>">
                                    <?php echo ucfirst($request['status']); ?>
                                </span>
                            </p>
                        </div>

                        <div class="mb-4">
                            <h5>Contact Information</h5>
                            <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($request['contact_number']); ?></p>
                        </div>

                        <?php if ($request['mechanic_name']): ?>
                        <div class="mb-4">
                            <h5>Assigned Mechanic</h5>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($request['mechanic_name']); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if ($request['completion_notes']): ?>
                        <div class="mb-4">
                            <h5>Service Notes</h5>
                            <p><?php echo nl2br(htmlspecialchars($request['completion_notes'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../../../includes/rider-footer.php'; ?>
</body>
</html>
