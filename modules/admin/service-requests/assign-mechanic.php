<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../config/database.php';
require_once '../../../models/ServiceRequest.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/modules/auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['request_id']) || !isset($_POST['mechanic_id'])) {
    header('Location: ' . BASE_URL . '/modules/admin/service-requests/list.php');
    exit();
}

$serviceRequest = new ServiceRequest($conn);
if ($serviceRequest->assignMechanic($_POST['request_id'], $_POST['mechanic_id'])) {
    header('Location: ' . BASE_URL . '/modules/admin/service-requests/view.php?id=' . $_POST['request_id'] . '&success=assigned');
} else {
    header('Location: ' . BASE_URL . '/modules/admin/service-requests/view.php?id=' . $_POST['request_id'] . '&error=assign_failed');
}
exit();
