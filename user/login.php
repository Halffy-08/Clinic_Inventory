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
                    echo "<script>alert('Welcome Staff!'); window.location.href='../views/dashboard.php';</script>";
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
    <title>Login | Clinic Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #764ba2; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { border-radius: 1rem; background: #fff; width: 400px; padding: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

<div class="login-card">
    <h2 class="text-center mb-4">Clinic Login</h2>
    
    <?php if($error): ?>
        <div class="alert alert-danger small text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" autocomplete="off">
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" 
                   name="email" 
                   class="form-control" 
                   required 
                   autocomplete="username">
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" 
                   name="password" 
                   class="form-control" 
                   required 
                   autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary w-100 rounded-pill">Sign In</button>
    </form>
</div>

</body>
</html>