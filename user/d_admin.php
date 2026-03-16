<?php
require "../app/conn.php";

$key = "Key@123456789";
$cipher = "AES-256-CBC";

$admin_email = "admin@gmail.com";
$admin_role = "admin";
$admin_pass = "Admin123";

function encryptData($data, $cipher, $key) {
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
 
    return base64_encode($iv . $encrypted);
}


$encrypted_email = encryptData($admin_email, $cipher, $key);
$encrypted_role = encryptData($admin_role, $cipher, $key);

$hashed_password = password_hash($admin_pass, PASSWORD_DEFAULT);


$sql = "INSERT INTO users (email, role, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
 
    $stmt->bind_param("sss", $encrypted_email, $encrypted_role, $hashed_password);

    if ($stmt->execute()) {
        echo "<h3>Admin user created successfully!</h3>";
        echo "Email (Encrypted): " . $encrypted_email . "<br>";
        echo "Role (Encrypted): " . $encrypted_role . "<br>";
        echo "Password (Hashed): " . $hashed_password . "<br><br>";
        echo "<a href='login.php'>Go to Login</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Prepare failed: " . $conn->error;
}
?>