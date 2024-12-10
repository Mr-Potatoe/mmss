<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../models/Mechanic.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../auth/login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $contact_number = trim($_POST['contact_number']);

    if (empty($name) || empty($contact_number)) {
        $error = "All fields are required";
    } else {
        $mechanic = new Mechanic($conn);
        $data = [
            'name' => $name,
            'contact_number' => $contact_number
        ];

        if ($mechanic->create($data)) {
            $success = "Mechanic added successfully";
        } else {
            $error = "Failed to add mechanic";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Mechanic - MotoService</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once '../../../includes/admin-navbar.php'; ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h3 mb-0">Add New Mechanic</h1>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="contact_number" class="form-label">Contact Number:</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Add Mechanic</button>
                                <a href="list.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once '../../../includes/admin-footer.php'; ?>
</body>
</html>
