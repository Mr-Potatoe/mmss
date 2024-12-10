<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/ServiceRequest.php';
require_once '../../../models/Mechanic.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ' . BASE_URL . '/modules/admin/service-requests/list.php');
    exit();
}

$serviceRequest = new ServiceRequest($conn);
$mechanic = new Mechanic($conn);

$request = $serviceRequest->getById($_GET['id']);
$mechanics = $mechanic->getActive();

if (!$request) {
    header('Location: ' . BASE_URL . '/modules/admin/service-requests/list.php');
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
    <link rel="stylesheet" href="../../../assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/admin-navbar.php'; ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0">Service Request Details</h1>
                        <a href="<?php echo BASE_URL; ?>/modules/admin/service-requests/list.php" class="btn btn-secondary">Back to List</a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Customer Information</h5>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($request['customer_name']); ?></p>
                                <p><strong>Contact:</strong> <?php echo htmlspecialchars($request['customer_contact']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($request['customer_email']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>Service Information</h5>
                                <p><strong>Service Type:</strong> <?php echo htmlspecialchars($request['service_name']); ?></p>
                                <p><strong>Schedule Date:</strong> <?php echo date('M d, Y', strtotime($request['schedule_date'])); ?></p>
                                <p><strong>Status:</strong> 
                                    <span class="badge bg-<?php echo $request['status'] === 'pending' ? 'warning' : 
                                        ($request['status'] === 'approved' ? 'success' : 'primary'); ?>">
                                        <?php echo ucfirst($request['status']); ?>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Schedule Details</h5>
                            <p><strong>Service Type:</strong> <?php echo htmlspecialchars($request['service_name']); ?></p>
                            <p><strong>Schedule Date:</strong> <?php echo date('M d, Y', strtotime($request['schedule_date'])); ?></p>
                            <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($request['customer_contact']); ?></p>
                        </div>

                        <?php if ($request['status'] === 'pending'): ?>
                        <div class="d-flex gap-2">
                            <a href="approve.php?id=<?php echo $request['request_id']; ?>" 
                               class="btn btn-success"
                               onclick="return confirm('Are you sure you want to approve this request?')">
                                Approve Request
                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignMechanicModal">
                                Assign Mechanic
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Mechanic Modal -->
    <div class="modal fade" id="assignMechanicModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Mechanic</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="assign-mechanic.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                        <div class="mb-3">
                            <label for="mechanic_id" class="form-label">Select Mechanic:</label>
                            <select class="form-select" id="mechanic_id" name="mechanic_id" required>
                                <option value="">Choose a mechanic...</option>
                                <?php foreach ($mechanics as $mech): ?>
                                <option value="<?php echo $mech['mechanic_id']; ?>">
                                    <?php echo htmlspecialchars($mech['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Assign Mechanic</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include_once '../../../includes/admin-footer.php'; ?>
</body>
</html> 