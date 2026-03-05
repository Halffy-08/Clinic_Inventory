<?php
session_start();
require_once '../app/conn.php';

// Validate ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM inventory WHERE id = '$id'");
$item = mysqli_fetch_assoc($result);

// Redirect if ID is not in database
if (!$item) { header("Location: dashboard.php"); exit(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $qty = (int)$_POST['quantity'];

    if (mysqli_query($conn, "UPDATE inventory SET item_name='$name', qty='$qty' WHERE id='$id'")) {
        header("Location: dashboard.php?msg=updated");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
<div class="container">
    <div class="col-md-5 mx-auto card shadow p-4 border-0" style="border-radius: 15px;">
        <h4 class="fw-bold text-primary mb-4">Edit Inventory Item</h4>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Item Name</label>
                <input type="text" name="item_name" class="form-control" value="<?= htmlspecialchars($item['item_name']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Quantity</label>
                <input type="number" name="quantity" class="form-control" value="<?= $item['qty']; ?>" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill">Update Product</button>
                <a href="dashboard.php" class="btn btn-link text-muted">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>