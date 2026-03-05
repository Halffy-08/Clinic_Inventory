<?php
require_once '../app/conn.php';

$error = ""; // Initialize error variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // 1. CHECK IF USERNAME ALREADY EXISTS
    $checkUser = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($checkUser) > 0) {
        // If username found, set an error message instead of crashing
        $error = "This email/username is already registered. Please try logging in.";
    } else {
        // 2. PROCEED WITH INSERT ONLY IF UNIQUE
        $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        
        if (mysqli_query($conn, $sql)) {
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
    <title>Create Account | Clinic Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f0f4f8; min-height: 100vh; display: flex; align-items: center; }
        .register-card { border: none; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .form-control, .form-select { border-radius: 10px; padding: 12px; border: 2px solid #eee; }
        .form-control:focus { border-color: #0d6efd; box-shadow: none; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card register-card p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                        <i class="bi bi-person-plus text-success fs-2"></i>
                    </div>
                    <h3 class="fw-bold">Join the Clinic</h3>
                    <p class="text-muted small">Create an account to start managing supplies</p>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert alert-danger small"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Choose a username" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Your Role</label>
                        <select name="role" class="form-select" required>
                            <option value="" selected disabled>Select Access Level</option>
                            <option value="staff">Staff (View/Add/Edit)</option>
                            <option value="admin">Admin (Full Control)</option>
                        </select>
                        <div class="form-text mt-2 small text-muted">
                            <i class="bi bi-info-circle me-1"></i> Admins can delete records and manage users.
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-sm">Register Account</button>
                        <a href="login.php" class="btn btn-link text-decoration-none text-muted small mt-2">Already have an account? Log In</a>
                    </div>
                </form>
            </div>
            <div class="text-center mt-4">
                <a href="index.php" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>