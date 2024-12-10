<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/User.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

$user = new User($conn);
$riders = $user->getAllRiders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Riders - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/admin-navbar.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Manage Riders</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($riders as $rider): ?>
                            <tr>
                                <td><?php echo $rider['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($rider['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($rider['email']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $rider['is_active'] ? 'success' : 'danger'; ?>">
                                        <?php echo $rider['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($rider['created_at'])); ?></td>
                                <td>
                                    <a href="view.php?id=<?php echo $rider['user_id']; ?>" 
                                       class="btn btn-sm btn-info">View</a>
                                    <a href="toggle-status.php?id=<?php echo $rider['user_id']; ?>" 
                                       class="btn btn-sm <?php echo $rider['is_active'] ? 'btn-danger' : 'btn-success'; ?>"
                                       onclick="return confirm('Are you sure you want to <?php echo $rider['is_active'] ? 'deactivate' : 'activate'; ?> this rider?')">
                                        <?php echo $rider['is_active'] ? 'Deactivate' : 'Activate'; ?>
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