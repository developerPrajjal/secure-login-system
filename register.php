<?php
session_start(); // start session if you need it later

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $message = "❌ Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $message = "❌ Password must be at least 6 characters.";
    } else {
        // Check if username exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "⚠️ Username already taken.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if (!$insert) {
                die("Prepare failed: " . $conn->error);
            }
            $insert->bind_param("ss", $username, $hashed);

            if ($insert->execute()) {
                header("Location: login.php?register=success");
                exit;
            } else {
                $message = "❌ Registration failed: " . $insert->error;
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register - Secure System</title>
  <link rel="stylesheet" href="assets/style.css" />
  <style>
    /* You can remove this if you have these styles in style.css */
    form {
      background: white;
      width: 400px;
      height: 420px;
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
      color: red;
      font-weight: 600;
      margin-bottom: 1rem;
      flex-shrink: 0;
      min-height: 1.5em;
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
  </style>
</head>
<body>

<form method="POST" action="register.php" novalidate>
  <h2>📝 Register</h2>
  <?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
  <?php else: ?>
    <div class="message">&nbsp;</div> <!-- keep height consistent -->
  <?php endif; ?>
  <input type="text" name="username" placeholder="Username" required autocomplete="username" />
  <input type="password" name="password" placeholder="Password" required autocomplete="new-password" />
  <input type="password" name="confirm_password" placeholder="Confirm Password" required autocomplete="new-password" />
  <button type="submit">Register</button>
  <p>Already have an account? <a href="login.php">Login here</a></p>
</form>

</body>
</html>
