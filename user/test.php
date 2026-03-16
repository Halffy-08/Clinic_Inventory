<?php
session_start();
require_once '../app/conn.php';

$decrypted_role = strtolower(trim(decryptData($found_user["role"]))); //

if ($decrypted_role === 'admin') {
    header("Location: ../admin/dashboard.php"); //
    exit();
} elseif ($decrypted_role === 'staff') {
    header("Location: ../user/staff_dashboard.php"); //
    exit();
}
?>