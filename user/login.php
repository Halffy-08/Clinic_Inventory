<?php
session_start();
require_once '../app/conn.php';

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $key = "Key@123456789";
    $cipher = "AES-256-CBC";

    $email_input = trim($_POST["email"] ?? '');
    $password_input = trim($_POST["password"] ?? '');

    if (empty($email_input) || empty($password_input)) {
        $error = "Please fill in all fields.";
    } else {
        // Fetch all users to decrypt and check manually
        $sql = "SELECT * FROM `users`"; 
        $result = $conn->query($sql);

        $found_user = null;

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Decrypt the username column (which is called 'email' in your current DB)
                $decrypted_email = decryptData($row["email"], $cipher, $key);
                
                if ($decrypted_email === $email_input) {
                    $found_user = $row;
                    break;
                }
            }
        }

        if ($found_user) {
            if (password_verify($password_input, $found_user['password'])) {
                // Decrypt the role
                $decrypted_role = decryptData($found_user["role"], $cipher, $key);

                session_regenerate_id(true);
                $_SESSION['user_id'] = $found_user['id'];
                $_SESSION['role'] = $decrypted_role;

                if ($decrypted_role === 'admin') {
                    // Redirecting to the single dashboard file
                    echo "<script>alert('Welcome Admin!'); window.location.href='../admin/dashboard.php';</script>";
                } elseif ($decrypted_role === 'staff') {
                    // Redirecting to the same single dashboard file
                    echo "<script>alert('Welcome Staff!'); window.location.href='../views/dashboard.php';</script>";
                } else {
                    $error = "Role not recognized: " . $decrypted_role;
                }
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
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

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 rounded-pill">Sign In</button>
    </form>
</div>

</body>
</html>