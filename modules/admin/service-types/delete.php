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
if ($serviceType->delete($_GET['id'])) {
    header('Location: list.php?success=deleted');
} else {
    header('Location: list.php?error=delete_failed');
}
exit(); 