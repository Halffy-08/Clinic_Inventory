<?php
session_start();
require_once '../app/conn.php';

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_input = trim(strtolower($_POST["email"] ?? ''));
    $password_input = $_POST["password"] ?? '';
    $role = "staff"; 

    if (empty($email_input) || empty($password_input)) {
        $error = "Please fill in all fields.";
    } else {
        $email_index = generateBlindIndex($email_input);
        
        // Check if exists
        $check = $conn->prepare("SELECT id FROM users WHERE email_index = ?");
        $check->bind_param("s", $email_index);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $enc_email = encryptData($email_input);
            $enc_role = encryptData($role);
            $hash_pass = password_hash($password_input, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (email, email_index, role, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $enc_email, $email_index, $enc_role, $hash_pass);

            if ($stmt->execute()) {
                echo "<script>alert('Staff Created!'); window.location.href='login.php';</script>";
                exit();
            }
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

                <form method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Username / Email</label>
                        <input type="text" name="email" class="form-control" placeholder="Enter username" required autocomplete="none">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Choose a password" required autocomplete="new-password">
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