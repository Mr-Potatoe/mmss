<?php
session_start();

require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/Mechanic.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

$mechanic = new Mechanic($conn);
$mechanics = $mechanic->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Mechanics - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/admin-navbar.php'; ?>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Manage Mechanics</h1>
            <a href="add.php" class="btn btn-primary">Add New Mechanic</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mechanics as $mech): ?>
                            <tr>
                                <td><?php echo $mech['mechanic_id']; ?></td>
                                <td><?php echo htmlspecialchars($mech['name']); ?></td>
                                <td><?php echo htmlspecialchars($mech['contact_number']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $mech['is_active'] ? 'success' : 'danger'; ?>">
                                        <?php echo $mech['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?php echo $mech['mechanic_id']; ?>" 
                                       class="btn btn-sm btn-primary">Edit</a>
                                    <a href="delete.php?id=<?php echo $mech['mechanic_id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this mechanic?')">Delete</a>
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
</body>
</html>
