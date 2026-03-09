<?php
// 1. Correct the path: go up one folder (../) then into app/
require '../app/conn.php'; 

// 2. Define the Admin details
$username = "admin@gmail.com";
$plain_password = "admin123";
$role = "admin";

// 3. Hash the password
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// 4. Use $conn (Ensure $conn is the variable name inside app/conn.php)
$stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, role) VALUES (?, ?, ?)");

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $role);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<h3>Success!</h3>";
        echo "Admin account created.<br>";
        echo "Username: <b>$username</b><br>";
        echo "Password: <b>$plain_password</b><br>";
        echo "<br><a href='login.php'>Proceed to Login</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // If it still says "Undefined variable $conn", open app/conn.php 
    // and check if you named the variable something else (like $db or $link).
    echo "Connection variable error: " . mysqli_error($conn);
}
?>