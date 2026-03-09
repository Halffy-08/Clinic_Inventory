<?php
session_start();
require_once '../app/conn.php';

// Security: Only allow logged-in Staff or Admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'])) {
    $item_id = mysqli_real_escape_string($conn, $_POST['item_id']);

    // 1. Check stock level
    $check = mysqli_query($conn, "SELECT qty FROM inventory WHERE id = '$item_id'");
    $row = mysqli_fetch_assoc($check);

    if ($row && $row['qty'] > 0) {
        // 2. Reduce quantity by 1
        $update = "UPDATE inventory SET qty = qty - 1 WHERE id = '$item_id'";
        
        if (mysqli_query($conn, $update)) {
            header("Location: dashboard.php?msg=stock_reduced");
        } else {
            header("Location: dashboard.php?error=failed");
        }
    } else {
        header("Location: dashboard.php?error=out_of_stock");
    }
    exit();
}
?>

<td class="text-center">
    <form action="reduce_medicine.php" method="POST" class="d-inline">
        <input type="hidden" name="item_id" value="<?= $row['id']; ?>">
        <button type="submit" 
                class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm fw-bold border-0" 
                onclick="return confirm('Confirm usage: Subtract 1 unit from inventory?')">
            <i class="bi bi-person-dash-fill me-1"></i> Patient Result
        </button>
    </form>

    <?php if ($_SESSION['role'] == 'admin'): ?>
        <div class="vr mx-2 text-muted opacity-25" style="height: 20px;"></div>
        <a href="edit_item.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-primary border-0 rounded-circle">
            <i class="bi bi-pencil-square"></i>
        </a>
        <a href="delete_item.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger border-0 rounded-circle" 
           onclick="return confirm('Delete this record?')">
            <i class="bi bi-trash3"></i>
        </a>
    <?php endif; ?>
</td>