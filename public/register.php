<?php
session_start();
require_once '../config/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm'] ?? '';

  if ($username && $password && $password === $confirm) {
    // Check if username already exists
    $check = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
    $check->execute([$username]);
    if ($check->fetch()) {
      $message = "Username already taken.";
    } else {
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
      $stmt->execute([$username, $hashed]);
      $_SESSION['toast'] = ['type' => 'success', 'message' => 'Admin registered successfully!'];
      header("Location: login.php");
      exit;
    }
  } else {
    $message = "Please fill all fields and confirm password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Admin</title>
      <!-- Favicon -->
      <link rel="icon" href="favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light" style="font-family: Inter, sans-serif;">
  <div class="container mt-5">
    <div class="card p-4 mx-auto" style="max-width: 450px;">
      <h3 class="mb-4 text-center text-primary">Register Admin</h3>

      <?php if ($message): ?>
        <div class="alert alert-warning"><?= $message ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label>Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Confirm Password</label>
          <input type="password" name="confirm" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">Create Admin</button>
      </form>

      <div class="mt-3 text-center">
        <a href="login.php">Already have an account? Login</a>
      </div>
    </div>
  </div>
</body>
</html>
