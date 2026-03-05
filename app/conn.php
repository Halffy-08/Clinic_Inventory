<?php
$conn = mysqli_connect("localhost", "root", "", "clinic_inventory");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Removed the 'else' echo so it doesn't break your layout later
?>