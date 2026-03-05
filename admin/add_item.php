<?php
session_start();
require_once '../app/conn.php';

if (!isset($_SESSION['user_id'])) { 
    header("Location: ../user/login.php"); 
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $qty = (int)$_POST['quantity'];

    // SQL query matches your table structure (item_name, qty)
    $sql = "INSERT INTO inventory (item_name, qty) VALUES ('$name', '$qty')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?msg=added");
        exit();
    } else {
        $error = "Database Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Supply</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow p-4 border-0" style="border-radius: 20px;">
                <div class="text-center mb-4">
                    <i class="bi bi-plus-circle text-primary display-4"></i>
                    <h3 class="fw-bold mt-2">Add New Supply</h3>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger small"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Item Name</label>
                        <input type="text" name="item_name" class="form-control" placeholder="e.g. Paracetamol" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Quantity</label>
                        <input type="number" name="quantity" class="form-control" placeholder="0" min="1" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill">Save Item</button>
                        <a href="dashboard.php" class="btn btn-link text-muted text-decoration-none">Back to Dashboard</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>