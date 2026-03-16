<?php
session_start();
require_once '../app/conn.php';

// Security: Redirect if not logged in as staff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../user/login.php");
    exit();
}

// Fetch supplies for viewing only
$query = "SELECT * FROM supplies";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Inventory | Clinic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
     
        .sidebar { min-height: 100vh; background: #ffffff; border-right: 1px solid #dee2e6; position: fixed; width: 250px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .nav-link { color: #495057; font-weight: 500; border-radius: 8px; margin-bottom: 5px; }
        .nav-link.active { color: #198754; background: #eafaf1; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="sidebar p-3">
    <h5 class="text-success fw-bold mb-4"><i class="bi bi-shield-check"></i> Staff Portal</h5>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link active" href="dashboard.php"><i class="bi bi-box-seam me-2"></i> View Inventory</a></li>
        <li class="nav-item"><a class="nav-link" href="add_item.php"><i class="bi bi-plus-lg me-2"></i> Update Stock</a></li>
        <li class="nav-item mt-5"><a class="nav-link text-danger" href="../user/logout.php"><i class="bi bi-door-open me-2"></i> Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Medical Inventory</h3>
            <p class="text-muted small">Logged in as Staff Member</p>
        </div>
        <span class="badge bg-white text-dark border p-2 shadow-sm">
            <i class="bi bi-person-badge me-1"></i> <?= htmlspecialchars($_SESSION['username']); ?>
        </span>
    </div>

    <div class="card p-0 overflow-hidden">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-success">
                <tr>
                    <th class="ps-4">Code</th>
                    <th>Item Name</th>
                    <th>Available Stock</th>
                    <th class="text-center">Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($items)): ?>
                <tr>
                    <td class="ps-4 text-muted small">#<?= $row['id']; ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($row['item_name']); ?></td>
                    <td>
                        <?php $qClass = ($row['qty'] < 10) ? 'bg-danger' : 'bg-success'; ?>
                        <span class="badge <?= $qClass; ?> px-3"><?= $row['qty']; ?> Units</span>
                    </td>
                    <td class="text-center">
                        <a href="edit_item.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-pencil-square me-1"></i> Edit Stock
                        </a>
                        </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>