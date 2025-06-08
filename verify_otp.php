<?php
session_start();
if ($_POST['otp'] == $_SESSION['otp'] && (time() - $_SESSION['otp_time']) < 300) {
    header("Location: dashboard.php");
} else {
    echo "Invalid or expired OTP.";
}
?>
