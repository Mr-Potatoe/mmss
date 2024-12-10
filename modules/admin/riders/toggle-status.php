<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/User.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit();
}

$user = new User($conn);
if ($user->toggleStatus($_GET['id'])) {
    header('Location: list.php?success=status_updated');
} else {
    header('Location: list.php?error=update_failed');
}
exit(); 