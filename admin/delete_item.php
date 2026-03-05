<?php
session_start();
require_once '../app/conn.php';

// Security: Only allow logged-in users with correct roles
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'staff'])) {
    header("Location: ../user/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Execute Delete
    $sql = "DELETE FROM inventory WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        // Redirect back with a 'deleted' status
        header("Location: dashboard.php?msg=deleted");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: dashboard.php");
}
exit();
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Delete</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 text-center p-4" style="border-radius: 20px;">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle text-danger display-1"></i>
                </div>
                <h4 class="fw-bold">Remove Item?</h4>
                <p class="text-muted">You are about to delete this item from the inventory. This action cannot be undone.</p>
                
                <div class="d-grid gap-2 mt-4">
                    <form method="POST" action="delete_item_process.php">
                        <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill">Confirm Delete</button>
                    </form>
                    <a href="dashboard.php" class="btn btn-light btn-lg rounded-pill border">Keep Item</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>