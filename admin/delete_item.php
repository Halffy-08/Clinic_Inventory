<?php
session_start();
require_once '../app/conn.php';

if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($conn, "DELETE FROM inventory WHERE id = $id");
    header("Location: dashboard.php?msg=deleted");
} else {
    header("Location: dashboard.php");
}
exit();