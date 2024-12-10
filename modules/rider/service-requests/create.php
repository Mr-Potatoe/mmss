<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/ServiceType.php';
require_once '../../../models/ServiceRequest.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'rider') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

$serviceType = new ServiceType($conn);
$types = $serviceType->getActive();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceRequest = new ServiceRequest($conn);
    $result = $serviceRequest->create([
        'user_id' => $_SESSION['user_id'],
        'service_type_id' => $_POST['service_type_id'],
        'schedule_date' => $_POST['schedule_date'],
        'contact_number' => $_POST['contact_number']
    ]);

    if ($result) {
        header('Location: history.php?success=created');
        exit();
    } else {
        $error = "Failed to create service request";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Service - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/rider-navbar.php'; ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">Request Service</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="service_type_id" class="form-label">Service Type</label>
                                <select class="form-select" id="service_type_id" name="service_type_id" required>
                                    <option value="">Select a service</option>
                                    <?php foreach ($types as $type): ?>
                                        <option value="<?php echo $type['service_type_id']; ?>">
                                            <?php echo htmlspecialchars($type['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="schedule_date" class="form-label">Preferred Date</label>
                                <input type="date" class="form-control" id="schedule_date" name="schedule_date" 
                                       min="<?php echo date('Y-m-d'); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" 
                                       required pattern="[0-9\-]+">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Submit Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../../../includes/rider-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
