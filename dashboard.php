<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Redirect based on role
if ($_SESSION['role'] == 'admin') {
    header("Location: admin.php");
} else {
    header("Location: index.php");
}
exit;
?>
