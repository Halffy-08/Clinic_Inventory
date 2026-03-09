<?php
require_once '../app/conn.php';

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize username input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    // Hash the password for security
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // SECURITY: Force role to 'staff'. 
    // This ignores any manual HTML tampering by the user.
    $role = "staff"; 

    // 1. CHECK IF USERNAME ALREADY EXISTS
    $check_stmt = mysqli_prepare($conn, "SELECT username FROM users WHERE username = ?");
    mysqli_stmt_bind_param($check_stmt, "s", $username);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $error = "This username is already registered. Please try logging in.";
    } else {
        // 2. PROCEED WITH INSERT (Using Prepared Statement)
        $insert_stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($insert_stmt, "sss", $username, $password, $role);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            header("Location: login.php?msg=registered");
            exit();
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration | Clinic Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f4f8; min-height: 100vh; display: flex; align-items: center; }
        .register-card { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .form-control { border-radius: 10px; padding: 12px; border: 2px solid #eee; }
        .btn-primary { border-radius: 10px; padding: 12px; font-weight: 600; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card register-card p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                        <i class="bi bi-person-badge text-primary fs-2"></i>
                    </div>
                    <h3 class="fw-bold">Staff Sign Up</h3>
                    <p class="text-muted small">Create an account to access the clinic inventory</p>
                </div>

                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger py-2 small border-0"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Username / Email</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Choose a password" required>
                    </div>

                    <div class="alert alert-light py-2 small border text-center mb-4">
                        <i class="bi bi-shield-check me-1"></i> Account Level: <strong>Staff</strong>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary shadow-sm">Create Staff Account</button>
                        <a href="login.php" class="btn btn-link text-decoration-none text-muted small mt-2">Back to Login</a>
                    </div>
                </form>