<?php
require_once __DIR__ . '/../../config/config.php';
?>

<?php
session_start();
session_destroy();
header('Location: ' . BASE_URL . '/modules/auth/login.php');
exit();
?>