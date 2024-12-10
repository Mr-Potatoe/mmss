<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/User.php';
require_once '../../../models/ServiceRequest.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit();
}

$user = new User($conn);
$serviceRequest = new ServiceRequest($conn);

$rider = $user->getById($_GET['id']);
$requests = $serviceRequest->getAllByUser($_GET['id']);

if (!$rider || $rider['role'] !== 'rider') {
    header('Location: list.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rider - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/admin-navbar.php'; ?>

    <div class="container py-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Rider Information</h3>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($rider['fullname']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($rider['email']); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-<?php echo $rider['is_active'] ? 'success' : 'danger'; ?>">
                                <?php echo $rider['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </p>
                        <p><strong>Joined:</strong> <?php echo date('M d, Y', strtotime($rider['created_at'])); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Service Request History</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Mechanic</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($requests as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request['service_name']); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($request['schedule_date'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo $request['status'] === 'pending' ? 'warning' : 
                                                    ($request['status'] === 'approved' ? 'success' : 'primary'); 
                                            ?>">
                                                <?php echo ucfirst($request['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $request['mechanic_name'] ?? 'Not Assigned'; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../../../includes/admin-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 