<?php
session_start();
require_once '../app/conn.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM inventory WHERE id = $id");
$item = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $qty = intval($_POST['qty']);

    $update = "UPDATE inventory SET item_name = '$name', qty = $qty WHERE id = $id";
    if (mysqli_query($conn, $update)) {
        header("Location: dashboard.php?msg=updated");
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
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow p-4">
                    <h4 class="fw-bold mb-4">Edit Item</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Item Name</label>
                            <input type="text" name="item_name" class="form-control" value="<?= htmlspecialchars($item['item_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="qty" class="form-control" value="<?= $item['qty'] ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Update Item</button>
                        <a href="dashboard.php" class="btn btn-link w-100 mt-2">Back to Dashboard</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>