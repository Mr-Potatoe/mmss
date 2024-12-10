<?php
session_start();
require_once '../../../config/database.php';
require_once '../../../models/Mechanic.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../auth/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $mechanic = new Mechanic($conn);
    $mechanic->delete($_GET['id']);
}

header('Location: list.php');
exit();
