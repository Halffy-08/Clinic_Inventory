<?php
// Ensure this path is correct for your folder structure
require "../app/conn.php"; 

$admin_email = "admin@gmail.com";
$admin_role = "admin";
$admin_pass = "Admin123";

// These functions from conn.php ensure the KEY matches your login script
$email_index = generateBlindIndex($admin_email);
$encrypted_email = encryptData($admin_email);
$encrypted_role = encryptData($admin_role);
$hashed_password = password_hash($admin_pass, PASSWORD_DEFAULT);

// We include the email_index column so login.php can find this row
$sql = "INSERT INTO users (email, email_index, role, password) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssss", $encrypted_email, $email_index, $encrypted_role, $hashed_password);

    if ($stmt->execute()) {
        echo "<div style='padding:20px; background:#d4edda; color:#155724; border:1px solid #c3e6cb;'>";
        echo "<h3>Admin account created!</h3>";
        echo "You can now log in with: <b>$admin_email</b>";
        echo "</div>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>