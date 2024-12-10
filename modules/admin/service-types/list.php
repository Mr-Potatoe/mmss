<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/ServiceType.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

$serviceType = new ServiceType($conn);
$types = $serviceType->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Types - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/admin-navbar.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Service Types</h1>
            <a href="add.php" class="btn btn-primary">Add New Service Type</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($types as $type): ?>
                            <tr>
                                <td><?php echo $type['service_type_id']; ?></td>
                                <td><?php echo htmlspecialchars($type['name']); ?></td>
                                <td><?php echo htmlspecialchars($type['description'] ?? 'No description'); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $type['is_active'] ? 'success' : 'danger'; ?>">
                                        <?php echo $type['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?php echo $type['service_type_id']; ?>" 
                                       class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete.php?id=<?php echo $type['service_type_id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this service type?')">
                                        Delete
                                    </a>
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
