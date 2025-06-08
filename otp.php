<?php
session_start();

// OTP expiry time (in seconds)
$otpExpiry = 300; // 5 minutes

if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_time'])) {
    die("❌ No OTP session found. Please login again.");
}

$otpVerified = false;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = $_POST['otp'];
    $currentTime = time();

    if ($currentTime - $_SESSION['otp_time'] > $otpExpiry) {
        unset($_SESSION['otp'], $_SESSION['otp_time']);
        $message = "⏰ OTP has expired. <a href='login.php'>Login again</a>";
    } elseif ($enteredOtp == $_SESSION['otp']) {
        unset($_SESSION['otp'], $_SESSION['otp_time']);
        $otpVerified = true;
    } else {
        $message = "❌ Invalid OTP. Try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 2rem 2.5rem;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            text-align: center;
            max-width: 400px;
        }
        input, button {
            margin-top: 1rem;
            padding: 10px;
            width: 100%;
            font-size: 1rem;
        }
        .button {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .button:hover {
            background: #0056b3;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
<?php if ($otpVerified): ?>
    <h2 class="success">✅ OTP Verified!</h2>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>.</p>
    <a class="button" href="dashboard.php">Go to Dashboard</a>
<?php else: ?>
    <h2>🔑 Enter OTP</h2>
    <?php if ($message): ?>
        <p class="error"><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST" action="otp.php">
        <input type="text" name="otp" placeholder="Enter 6-digit OTP" required pattern="\d{6}">
        <button type="submit">Verify</button>
        <p style="font-size: 0.9rem;">OTP expires in 5 minutes.</p>
    </form>
<?php endif; ?>
</div>

</body>
</html>
