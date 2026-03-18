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
        body { 
            background-color: #f0f4f8; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
        }
        .register-card { 
            border: none; 
            border-radius: 25px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.05); 
        }
        .form-control { 
            border-radius: 8px; 
            padding: 12px; 
            border: none;
            background-color: #eef3ff; /* Matches the light blue/grey in your login screenshot */
        }
        .form-control:focus {
            background-color: #e9effd;
            box-shadow: none;
            border: 1px solid #198754;
        }
        .btn-portal { 
            border-radius: 8px; 
            padding: 12px; 
            font-weight: 500; 
            background-color: #e9f7ef; 
            color: #198754;
            border: none;
            transition: 0.3s;
        }
        .btn-portal:hover {
            background-color: #198754;
            color: white;
        }
        .text-portal {
            color: #198754;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card register-card p-4 p-md-5 bg-white">
                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-shield-check text-portal fs-3 me-2"></i>
                    <h3 class="fw-bold text-portal mb-0">Staff Sign Up</h3>
                </div>
                
                <p class="text-muted small mb-4">Create an account to access the clinic inventory</p>

                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger py-2 small border-0"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Email Address</label>
                        <input type="text" name="email" class="form-control" placeholder="Enter email" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="********" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-portal d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-plus me-2"></i> Create Staff Account
                        </button>
                        <div class="text-center mt-3">
                            <a href="login.php" class="back-link">
                                <i class="bi bi-arrow-left me-1"></i> Back to Login
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