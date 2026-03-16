<?php
session_start();
require_once '../app/conn.php';

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_input = trim($_POST["email"] ?? '');
    $password_input = trim($_POST["password"] ?? '');
    $role = "staff"; // Forced to staff for this specific page

    if (empty($email_input) || empty($password_input)) {
        $error = "Please fill in all fields.";
    } else {
        // 1. Check if user already exists using Blind Index
        $email_index = generateBlindIndex($email_input);
        
        $check_sql = "SELECT id FROM `users` WHERE email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $email_index);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "This email/username is already registered.";
        } else {
            // 2. Prepare data for insertion
            $encrypted_email = encryptData($email_input);
            $encrypted_role = encryptData($role);
            $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);

            // 3. Insert new staff record
            $insert_sql = "INSERT INTO `users` (email, email_index, password, role) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ssss", $encrypted_email, $email, $hashed_password, $encrypted_role);

            if ($insert_stmt->execute()) {
                echo "<script>alert('Staff Account Created Successfully!'); window.location.href='login.php';</script>";
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
            $insert_stmt->close();
        }
        $stmt->close();
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
        .btn-primary { border-radius: 10px; padding: 12px; font-weight: 600; background-color: #0d6efd; }
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
                        <input type="text" name="email" class="form-control" placeholder="Enter username" required>
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
            </div>
        </div>
    </div>
</div>
</body>
</html>