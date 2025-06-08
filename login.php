<?php
session_start();
require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'recaptcha_verify.php'; // your recaptcha check

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, failed_attempts, last_attempt FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($userId, $hashedPassword, $failedAttempts, $lastAttempt);

    if ($stmt->fetch()) {
        if ($failedAttempts >= 5 && (time() - strtotime($lastAttempt)) < 300) {
            $message = "🚫 Too many failed attempts. Please try again after 5 minutes.";
        } elseif (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;

            $conn->query("UPDATE users SET failed_attempts = 0 WHERE username = '$username'");

            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_time'] = time();

            // Show OTP on screen for testing (replace this with mail() in production)
            $message = "<span style='color:green;'>Your OTP is: <strong>$otp</strong></span>";

            // Instead of redirecting now, show OTP and a link to otp.php
            // User will enter OTP on otp.php page
        } else {
            $conn->query("UPDATE users SET failed_attempts = failed_attempts + 1, last_attempt = NOW() WHERE username = '$username'");
            include 'log_failed_login.php';
            $message = "❌ Incorrect credentials.";
        }
    } else {
        $message = "⚠️ User not found.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login - Secure System</title>
  <link rel="stylesheet" href="assets/style.css" />
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <style>
   form {
  background: white;
  width: 400px;
  height: 460px; /* slightly taller to fit OTP message */
  padding: 2rem 2.5rem;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  box-sizing: border-box;
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

h2 {
  margin: 0 0 1rem 0;
  font-size: 1.5rem;
  flex-shrink: 0;
}

input, button {
  width: 100%;
  padding: 10px;
  font-size: 1rem;
  border-radius: 5px;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

input {
  margin-bottom: 1rem;
  flex-shrink: 0;
}

button {
  background: #007bff;
  color: white;
  border: none;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s ease;
  flex-shrink: 0;
}

button:hover {
  background: #0056b3;
}

.message {
  font-weight: 600;
  margin-bottom: 1rem;
  flex-shrink: 0;
  min-height: 1.5em;
  word-break: break-word;
}

p {
  margin: 0;
  font-size: 0.9rem;
  flex-shrink: 0;
}

a {
  color: inherit;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

.g-recaptcha {
  margin-bottom: 1rem;
  flex-shrink: 0;
  min-height: 78px;
}
  </style>
</head>
<body>

<form method="POST" action="login.php">
  <h2>🔐 Secure Login</h2>
  <?php if ($message): ?>
    <div class="message"><?php echo $message; ?></div>
  <?php endif; ?>
  <input type="text" name="username" placeholder="Username" required autocomplete="username" />
  <input type="password" name="password" placeholder="Password" required autocomplete="current-password" />

  <!-- Google reCAPTCHA -->
  <div class="g-recaptcha" data-sitekey="6LfQR1krAAAAAKnMW5NVlK8dsimueFSrN0fDaQuh"></div>

  <button type="submit">Login</button>
  <p>Don't have an account? <a href="register.php">Register here</a></p>

  <?php if (isset($_SESSION['otp'])): ?>
    <p><a href="otp.php">Go to OTP Verification</a></p>
  <?php endif; ?>
</form>

</body>
</html>
