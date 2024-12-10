<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/ServiceRequest.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: ' . BASE_URL . '/modules/admin/service-requests/list.php');
    exit();
}

$serviceRequest = new ServiceRequest($conn);
if ($serviceRequest->approve($_GET['id'])) {
    header('Location: ' . BASE_URL . '/modules/admin/service-requests/view.php?id=' . $_GET['id'] . '&success=approved');
} else {
    header('Location: ' . BASE_URL . '/modules/admin/service-requests/view.php?id=' . $_GET['id'] . '&error=approve_failed');
}
exit();
