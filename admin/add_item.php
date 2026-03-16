<?php
session_start();
require_once '../app/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_with_iv($conn, $_POST['item_name']); 
    $qty = intval($_POST['qty']);

    $sql = "INSERT INTO inventory (item_name, qty) VALUES ('$name', $qty)";
    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?msg=added");
    } else {
        $error = "Error adding item: " . mysqli_error($conn);
    }
}

function mysqli_real_escape_with_iv($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Supply | Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow p-4">
                    <h4 class="fw-bold mb-4">Add New Supply</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" name="item_name" class="form-control" placeholder="e.g. Paracetamol" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Initial Quantity</label>
                            <input type="number" name="qty" class="form-control" min="0" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Save Item</button>
                            <a href="dashboard.php" class="btn btn-outline-secondary w-100">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>