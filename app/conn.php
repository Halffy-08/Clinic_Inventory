<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "clinic_inventory"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Encryption
$cipher = "aes-256-cbc";
$key = "12345678901234567890123456789012"; // 32 chars

if (!function_exists('encryptData')) {
    function encryptData($data) {
        global $cipher, $key;
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $ciphertext_raw);
    }
}

if (!function_exists('decryptData')) {
    function decryptData($data) {
        global $cipher, $key;
        $c = base64_decode($data);
        $ivlen = openssl_cipher_iv_length($cipher);
        if (strlen($c) < $ivlen) return false; 
        $iv = substr($c, 0, $ivlen);
        $ciphertext_raw = substr($c, $ivlen);
        return openssl_decrypt($ciphertext_raw, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    }
}

if (!function_exists('generateBlindIndex')) {
    function generateBlindIndex($data) {
        global $key;
        return hash_hmac('sha256', strtolower(trim($data)), $key);
    }
}
?>