<?php
session_start();
require_once '../app/conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // Select the user and their role
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($pass, $row['password'])) {
            
            // Store common data in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role']; 

            // REDIRECT LOGIC FIX:
            // Since your files are in the 'admin' folder, send both roles there.
            if ($row['role'] == 'admin' || $row['role'] == 'staff') {
                header("Location: ../admin/dashboard.php");
                exit();
            } else {
                $error = "Access Denied: Your role is not recognized.";
            }
            
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
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
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            background: #ffffff;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle text-primary display-3"></i>
                        <h2 class="fw-bold mt-2">Clinic Login</h2>
                        <p class="text-muted">Enter your credentials</p>
                    </div>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger py-2 small text-center"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="userInput" placeholder="Username" required>
                            <label for="userInput">Username</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" name="password" class="form-control" id="passInput" placeholder="Password" required>
                            <label for="passInput">Password</label>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">Sign In</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>