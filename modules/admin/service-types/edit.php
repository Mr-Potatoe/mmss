<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/ServiceType.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit();
}

$serviceType = new ServiceType($conn);
$type = $serviceType->getById($_GET['id']);

if (!$type) {
    header('Location: list.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($serviceType->update($_GET['id'], $_POST['name'], $_POST['description'], isset($_POST['is_active']))) {
        header('Location: list.php?success=updated');
        exit();
    } else {
        $error = 'Failed to update service type';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service Type - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/admin-navbar.php'; ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">Edit Service Type</h2>
                        <a href="list.php" class="btn btn-secondary">Back to List</a>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($type['name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" 
                                          rows="3"><?php echo htmlspecialchars($type['description'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                       <?php echo $type['is_active'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Service Type</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../../../includes/admin-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
