<?php
session_start();
require_once '../app/conn.php';

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // We use the $key and $cipher from conn.php automatically
    $email_input = trim(strtolower($_POST["email"] ?? ''));
    $password_input = $_POST["password"] ?? '';

    if (empty($email_input) || empty($password_input)) {
        $error = "Please fill in all fields.";
    } else {
        // Fast search using the blind index
        $email_index = generateBlindIndex($email_input);
        
        $sql = "SELECT * FROM `users` WHERE email_index = ?"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email_index);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password_input, $row['password'])) {
                // Decrypt role using conn.php settings
                $decrypted_role = decryptData($row["role"]);

                session_regenerate_id(true);
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $decrypted_role;

                if ($decrypted_role === 'staff') {
                    echo "<script>alert('Welcome Staff!'); window.location.href='../user/staff_dashboard.php';</script>";
                    exit();
                } else if ($decrypted_role === 'admin') {
                    echo "<script>alert('Welcome Admin!'); window.location.href='../admin/dashboard.php';</script>";
                    exit();
                } else {
                    $error = "Access Denied: Role not recognized.";
                }
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found. Please check your credentials.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Clinic Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        /* Matching the screenshot's clean, light grey background */
        body { 
            background-color: #f8f9fa; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card { 
            background: #ffffff; 
            width: 100%;
            max-width: 400px; 
            padding: 2.5rem; 
            border-radius: 1.5rem; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
        }

        /* The specific Teal/Green from your image */
        .text-clinic-green {
            color: #0d7a5f; 
        }

        /* The "View Inventory" button style from the image */
        .btn-inventory {
            background-color: #e9f7ef;
            color: #0d7a5f;
            border: none;
            font-weight: 500;
            text-align: left;
            padding: 12px 20px;
            transition: all 0.2s;
        }

        .btn-inventory:hover {
            background-color: #d4ede0;
            color: #0a634d;
        }

        /* The "Logout" style from the image */
        .btn-logout {
            color: #dc3545;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            border: none;
            padding: 10px 0;
        }

        .btn-logout:hover {
            color: #a71d2a;
        }

        .form-control:focus {
            border-color: #0d7a5f;
            box-shadow: 0 0 0 0.25rem rgba(13, 122, 95, 0.1);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-shield-check text-clinic-green fs-3 me-2"></i>
        <h2 class="h4 mb-0 fw-bold text-clinic-green">Staff Portal</h2>
    </div>
    
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger border-0 small text-center mb-4" style="background-color: #fff5f5; color: #c0392b;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Email Address</label>
            <input type="email" name="email" class="form-control form-control-lg border-light-subtle bg-light" required>
        </div>
        
        <div class="mb-4">
            <label class="form-label small fw-bold text-secondary">Password</label>
            <input type="password" name="password" class="form-control form-control-lg border-light-subtle bg-light" required>
        </div>

        <button type="submit" class="btn btn-inventory w-100 rounded-3 mb-3 d-flex align-items-center">
            <i class="bi bi-box-arrow-in-right me-3 fs-5"></i>Sign In to Portal
        </button>

        <hr class="text-muted opacity-25">

        <button type="button" class="btn-logout">
            <i class="bi bi-door-open fs-5"></i> Cancel / Exit
        </button>
    </form>
</div>

</body>
</html>