<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body {
      font-family: Arial;
      background-color: #f4f4f4;
      text-align: center;
      padding-top: 100px;
    }
    .box {
      background: white;
      padding: 2rem;
      display: inline-block;
      border-radius: 10px;
      box-shadow: 0 0 10px #ccc;
    }
    a.button {
      display: inline-block;
      margin-top: 1rem;
      padding: 10px 20px;
      background: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
    a.button:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

<div class="box">
  <h2>👋 Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
  <p>You’ve successfully logged into the secure system.</p>

  <a href="logout.php" class="button">Logout</a>
</div>

</body>
</html>
