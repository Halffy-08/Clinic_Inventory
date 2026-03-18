<?php
session_start();
require_once '../app/conn.php';


if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'staff'])) {
    header("Location: ../user/login.php");
    exit();
}


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
        :root {
            --portal-teal: #198754;
            --portal-bg: #f0f4f8;
            --sidebar-active: #e9f7ef;
            --input-fill: #e8f0fe;
        }

        body { background-color: var(--portal-bg); font-family: 'Inter', sans-serif; }
        
        /* Sidebar Styling */
        .sidebar { 
            min-height: 100vh; 
            background: #ffffff; 
            border-right: 1px solid #e2e8f0; 
            position: fixed; 
            width: 250px; 
            box-shadow: 4px 0 15px rgba(0,0,0,0.02);
        }

        .main-content { margin-left: 250px; padding: 40px; }

        /* Sidebar Navigation */
        .nav-link { 
            color: #64748b; 
            font-weight: 500; 
            border-radius: 10px; 
            margin-bottom: 8px; 
            padding: 12px 15px;
            transition: all 0.2s;
        }
        
        .nav-link:hover { color: var(--portal-teal); background: #f8fafc; }

        .nav-link.active { 
            color: var(--portal-teal); 
            background: var(--sidebar-active); 
        }

        /* Card & Table Styling */
        .card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); }
        
        .table thead { background-color: #f8fafc; }
        .table thead th { 
            border: none; 
            color: #64748b; 
            font-weight: 600; 
            text-transform: uppercase; 
            font-size: 0.75rem; 
            letter-spacing: 0.5px;
        }

        /* Action Buttons */
        .btn-patient { 
            background-color: #fff9e6; 
            color: #d97706; 
            border: 1px solid #fef3c7;
            transition: all 0.2s;
        }
        .btn-patient:hover { 
            background-color: #d97706; 
            color: white; 
            transform: translateY(-1px);
        }

        .badge-stock { padding: 8px 12px; border-radius: 8px; font-weight: 600; }
        .text-portal { color: var(--portal-teal); }
        .bg-portal-light { background-color: var(--input-fill); }
    </style>
</head>
<body>

<div class="sidebar p-4">
    <div class="d-flex align-items-center mb-5">
        <i class="bi bi-shield-check text-portal fs-3 me-2"></i>
        <h5 class="text-portal fw-bold mb-0">
            <?= ($_SESSION['role'] == 'admin') ? 'Clinic Admin' : 'Staff Portal'; ?>
        </h5>
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="dashboard.php">
                <i class="bi bi-grid-1x2 me-2"></i> Inventory
            </a>
        </li>

        <?php if ($_SESSION['role'] == 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link" href="add_item.php">
                <i class="bi bi-plus-circle me-2"></i> Add Supply
            </a>
        </li>
        <?php endif; ?>

        <li class="nav-item mt-auto">
            <a class="nav-link text-danger opacity-75" href="../user/register.php">
                <i class="bi bi-box-arrow-left me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Medical Supplies</h3>
        </div>
        
        <div class="dropdown">
            <div class="bg-white border rounded-pill px-3 py-2 shadow-sm d-flex align-items-center">
                <i class="bi bi-person-circle me-2 text-portal"></i> 
                <span class="small fw-semibold me-2"><?= htmlspecialchars($_SESSION['user_id']); ?></span>
                <span class="badge bg-portal-light text-portal rounded-pill" style="font-size: 0.7rem;">
                    <?= strtoupper($_SESSION['role']); ?>
                </span>
            </div>
        </div>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 py-3">
            <i class="bi bi-check-circle-fill me-2 text-success"></i> 
            <span class="small fw-medium text-dark">Action completed successfully!</span>
        </div>
    <?php endif; ?>

    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Item Name</th>
                        <th class="py-3">Quantity Status</th>
                        <th class="text-center py-3">Quick Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($items)): ?>
                    <tr>
                        <td class="ps-4">
                            <span class="text-muted font-monospace small">#<?= $row['id']; ?></span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($row['item_name']); ?></div>
                        </td>
                        <td>
                            <?php if($row['qty'] <= 5): ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger badge-stock">
                                    <i class="bi bi-exclamation-triangle me-1"></i> <?= $row['qty']; ?> Low Stock
                                </span>
                            <?php else: ?>
                                <span class="badge bg-portal-light text-portal badge-stock">
                                    <?= $row['qty']; ?> Units
                                </span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <form action="reduce_medicine.php" method="POST" class="d-inline">
                                <input type="hidden" name="item_id" value="<?= $row['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-patient rounded-pill px-3 fw-bold" 
                                        onclick="return confirm('Reduce stock by 1 unit?')">
                                    <i class="bi bi-person-dash me-1"></i> Dispense
                                </button>
                            </form>

                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                <a href="edit_item.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-secondary border-0 rounded-circle ms-2">
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>