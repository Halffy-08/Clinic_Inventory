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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supply | Clinic Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --portal-teal: #198754;
            --portal-bg: #f0f4f8;
            --input-fill: #e8f0fe; 
        }

        body { 
            background-color: var(--portal-bg); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
        }

        /* Increased border-radius and shadow to match the Signup card */
        .supply-card { 
            border: none; 
            border-radius: 25px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.05); 
        }

        .portal-header {
            color: var(--portal-teal);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-control { 
            border: none;
            border-radius: 10px; 
            padding: 14px; /* Slightly larger padding for better feel */
            background-color: var(--input-fill);
        }

        .form-control:focus {
            background-color: #deebff;
            box-shadow: none;
            border: 1px solid #ced4da;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .btn-save { 
            border-radius: 10px; 
            padding: 14px; 
            font-weight: 600; 
            background-color: #e9f7ef; 
            color: var(--portal-teal);
            border: none;
            transition: all 0.2s ease-in-out;
            margin-top: 10px;
        }

        .btn-save:hover {
            background-color: var(--portal-teal);
            color: white;
            transform: translateY(-1px);
        }

        .btn-cancel {
            font-size: 0.9rem;
            color: #adb5bd;
            text-decoration: none;
            transition: 0.2s;
        }
        
        .btn-cancel:hover {
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card supply-card p-4 p-md-5 bg-white">
                
                <div class="portal-header mb-4">
                    <i class="bi bi-plus-circle-dotted fs-2"></i>
                    <h3 class="fw-bold mb-0">Add New Supply</h3>
                </div>
                
                <p class="text-muted small mb-4">Enter the details below to update the clinic inventory.</p>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" class="form-control" placeholder="e.g. Paracetamol" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Initial Quantity</label>
                        <input type="number" name="qty" class="form-control" placeholder="0" min="0" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-save shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i> Save to Inventory
                        </button>
                        <div class="text-center mt-2">
                            <a href="dashboard.php" class="btn-cancel">
                                <i class="bi bi-arrow-left me-1"></i> Cancel and go back
                            </a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>