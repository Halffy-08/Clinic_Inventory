<?php
session_start();
require_once '../app/conn.php';

// Security: Only allow logged-in Admin or Staff
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'staff'])) {
    header("Location: ../user/login.php");
    exit();
}

// Fetch all inventory items
$items = mysqli_query($conn, "SELECT * FROM inventory ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar { min-height: 100vh; background: #ffffff; border-right: 1px solid #dee2e6; position: fixed; width: 250px; }
        .main-content { margin-left: 250px; padding: 30px; }
        .nav-link { color: #495057; font-weight: 500; border-radius: 8px; margin-bottom: 5px; }
        .nav-link.active { color: #0d6efd; background: #f0f7ff; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-patient { transition: all 0.2s; }
        .btn-patient:hover { transform: scale(1.05); }
    </style>
</head>
<body>

<div class="sidebar p-3">
    <h5 class="text-primary fw-bold mb-4">
        <i class="bi bi-hospital"></i> <?= ($_SESSION['role'] == 'admin') ? 'Clinic Admin' : 'Staff Portal'; ?>
    </h5>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="dashboard.php"><i class="bi bi-grid-1x2 me-2"></i> Inventory</a>
        </li>

        <?php if ($_SESSION['role'] == 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link" href="add_item.php"><i class="bi bi-plus-circle me-2"></i> Add Supply</a>
        </li>
        <?php endif; ?>

        <li class="nav-item mt-5">
            <a class="nav-link text-danger" href="../user/logout.php"><i class="bi bi-box-arrow-left me-2"></i> Logout</a>
        </li>
    </ul>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Medical Supplies</h3>
        <span class="badge bg-white text-dark border p-2 shadow-sm">
            <i class="bi bi-person-circle me-1 text-primary"></i> 
            <?= htmlspecialchars($_SESSION['username']); ?> (<?= ucfirst($_SESSION['role']); ?>)
        </span>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> 
            <?= ($_GET['msg'] == 'stock_reduced') ? 'Patient result recorded. Stock reduced.' : 'Action completed successfully!'; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error']) && $_GET['error'] == 'out_of_stock'): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-octagon-fill me-2"></i> Error: Item is out of stock!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card p-0 overflow-hidden">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($items)): ?>
                <tr>
                    <td class="ps-4 text-muted small">#<?= $row['id']; ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($row['item_name']); ?></td>
                    <td>
                        <span class="badge px-3 rounded-pill <?= ($row['qty'] <= 5) ? 'bg-danger' : 'bg-info text-dark'; ?>">
                            <?= $row['qty']; ?> Units
                        </span>
                    </td>
                    
                    <td class="text-center">
                        <form action="reduce_medicine.php" method="POST" class="d-inline">
                            <input type="hidden" name="item_id" value="<?= $row['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm fw-bold btn-patient" 
                                    onclick="return confirm('Use 1 unit for this patient?')">
                                <i class="bi bi-person-dash-fill me-1"></i> Patient Result
                            </button>
                        </form>

                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <div class="vr mx-2 text-muted opacity-25" style="height: 20px; vertical-align: middle;"></div>
                            <a href="edit_item.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="delete_item.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0 rounded-circle" 
                               onclick="return confirm('Delete this record?')">
                                <i class="bi bi-trash3"></i>
                            </a>
                        <?php endif; ?>
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