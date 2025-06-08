<?php
$conn = new mysqli("localhost", "root", "", "secure_login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
